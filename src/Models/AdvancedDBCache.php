<?php
namespace App\Models;

use Predis\Client;

class AdvancedDBCache
{
    protected $db;
    protected $redis;
    protected $ttl;
    protected $namespace;

    public function __construct(DB $db, int $ttl = 600, ?string $namespace = null)
    {
        $this->db = $db;
        $this->ttl = $ttl;
        $this->namespace = $namespace ?? $db->getTable();

        $this->redis = new Client([
            'scheme'   => 'tcp',
            'host'     => '127.0.0.1',
            'port'     => 6379,
            // 'password' => '',
        ]);
    }

    public function get(bool $forceRefresh = false)
    {
        $key = $this->generateKey();
        if (!$forceRefresh && $this->redis->exists($key)) {
            return unserialize($this->redis->get($key));
        }
        $result = $this->db->get();
        $this->redis->setex($key, $this->ttl, serialize($result));
        return $result;
    }

    public function first(bool $forceRefresh = false)
    {
        $key = $this->generateKey() . '_first';
        if (!$forceRefresh && $this->redis->exists($key)) {
            return unserialize($this->redis->get($key));
        }
        $result = $this->db->first();
        $this->redis->setex($key, $this->ttl, serialize($result));
        return $result;
    }

    public function count(): int
    {
        $key = $this->generateKey() . '_count';
        if ($this->redis->exists($key)) {
            return unserialize($this->redis->get($key));
        }
        $count = $this->db->count();
        $this->redis->setex($key, $this->ttl, serialize($count));
        return $count;
    }

    public function insert(array $data) { $res = $this->db->insert($data); $this->clearCache(); return $res; }
    public function update(array $data = []) { $res = $this->db->update($data); $this->clearCache(); return $res; }
    public function delete() { $res = $this->db->delete(); $this->clearCache(); return $res; }

    public function clearCache(): void
    {
        $pattern = $this->namespace . ':*';
        $keys = $this->redis->keys($pattern);
        foreach ($keys as $key) {
            $this->redis->del($key);
        }
    }

    public function deleteKey(string $key): void
    {
        if ($this->redis->exists($key)) {
            $this->redis->del($key);
        }
    }

    public function set($value, ?int $ttl = null): void
    {
        $this->redis->setex($this->generateKey(), $ttl ?? $this->ttl, serialize($value));
    }

    protected function generateKey(): string
    {
        $params = [
            'table'    => $this->db->getTable(),
            'where'    => $this->db->getWhere(),
            'whereIn'  => $this->db->getWhereIn(),
            'whereRaw' => $this->db->getWhereRaw(),
            'orderBy'  => $this->db->getOrderBy(),
            'limit'    => $this->db->getLimit(),
            'offset'   => $this->db->getOffset(),
            'join'     => $this->db->getJoin(),
            'select'   => $this->db->getSelect(),
        ];
        return $this->namespace . ':' . md5(serialize($params));
    }
}
