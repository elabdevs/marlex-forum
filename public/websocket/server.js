import fs from 'fs';
import http from 'http';
import https from 'https';
import { WebSocketServer } from 'ws';
import dotenv from 'dotenv';

dotenv.config();

const HTTP_PORT = process.env.WS_PORT || 80;
const HTTPS_PORT = process.env.WSS_PORT || 443;
const AUTH_SERVER = "https://marlexforum.rf.gf/api/checkAuthToken";

const clients = new Map();

function checkAuthToken(token) {
  return new Promise((resolve, reject) => {
    if (!token) return resolve({ ok: false });

    const postData = `authToken=${encodeURIComponent(token)}`;

    const req = http.request(AUTH_SERVER, {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
        "Content-Length": Buffer.byteLength(postData)
      }
    }, (res) => {
      let body = "";
      res.on("data", chunk => body += chunk);
      res.on("end", () => {
        try {
          const json = JSON.parse(body);
          if (json.status === true && json.data?.is_admin === 1) {
            resolve({ ok: true });
          } else {
            resolve({ ok: false });
          }
        } catch (err) {
          resolve({ ok: false });
        }
      });
    });

    req.on("error", reject);
    req.write(postData);
    req.end();
  });
}


function broadcast(message) {
  for (const [client] of clients) {
    if (client.readyState === 1) {
      client.send(JSON.stringify(message));
    }
  }
}

async function handleRequest(req, res, isSecure = false) {
  const url = new URL(req.url, `${isSecure ? "https" : "http"}://${req.headers.host}`);

  if (url.pathname === '/broadcast') {
    const token = req.headers['authorization'];
    console.log('Broadcast isteği alındı, token:', token);

    const auth = await checkAuthToken(token);
    if (!auth.ok) {
      res.writeHead(403, { 'Content-Type': 'application/json' });
      res.end(JSON.stringify({ status: 'error', message: 'Unauthorized' }));
      return;
    }

    const msg = url.searchParams.get('msg') || 'Boş mesaj';

    broadcast({ type: 'admin_broadcast', text: msg });

    res.writeHead(200, { 'Content-Type': 'application/json' });
    res.end(JSON.stringify({ status: 'ok', sent: msg }));
    return;
  }

  res.writeHead(200, { 'Content-Type': 'text/plain' });
  res.end('WS Server is running');
}

const httpServer = http.createServer((req, res) => handleRequest(req, res, false));
httpServer.listen(HTTP_PORT, () => {
  console.log(`⚡ WS server running on port ${HTTP_PORT}`);
});

let httpsServer = null;
try {
  // Self-signed sertifika kullan
  const key = fs.readFileSync('./cert/server.key');
  const cert = fs.readFileSync('./cert/server.cert');

  httpsServer = https.createServer({ key, cert }, (req, res) => handleRequest(req, res, true));
  httpsServer.listen(HTTPS_PORT, '0.0.0.0', () => {
    console.log(`🔒 WSS server running on port ${HTTPS_PORT}`);
  });
} catch (err) {
  console.log('SSL sertifikası bulunamadı, sadece WS çalışacak', err);
}


function setupWebSocket(server) {
  const wss = new WebSocketServer({ server });

  wss.on('connection', (ws, req) => {
    console.log('Yeni WS bağlantısı:', req.socket.remoteAddress);

    ws.on('message', (msg) => {
      try {
        const data = JSON.parse(msg.toString());

        if (data.type === 'identify') {
          clients.set(ws, data.userId);
        } else if (data.type === 'message') {
          const { type, to, from, text } = data;
          for (const [client, userId] of clients) {
            if (userId === to && client.readyState === 1) {
              client.send(JSON.stringify({ type, from, text }));
            }
          }
        } else if (data.type === 'typing' || data.type === 'stop_typing') {
          const { type, to, from } = data;
          for (const [client, userId] of clients) {
            if (userId === to && client.readyState === 1) {
              client.send(JSON.stringify({ type, from }));
            }
          }
        }
      } catch (err) {
        console.error('Mesaj işleme hatası:', err);
      }
    });

    ws.on('close', () => {
      clients.delete(ws);
    });
  });
}

setupWebSocket(httpServer);
if (httpsServer) setupWebSocket(httpsServer);
