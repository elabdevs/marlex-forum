document.addEventListener("DOMContentLoaded", () => {
  const registerForm = document.getElementById("registerForm")
  const fullNameInput = document.getElementById("fullName")
  const usernameInput = document.getElementById("username")
  const emailInput = document.getElementById("email")
  const passwordInput = document.getElementById("password")
  const confirmPasswordInput = document.getElementById("confirmPassword")
  const passwordToggle = document.getElementById("passwordToggle")
  const confirmPasswordToggle = document.getElementById("confirmPasswordToggle")
  const registerButton = document.getElementById("registerButton")
  const agreeTermsCheckbox = document.getElementById("agreeTerms")
  const newsletterCheckbox = document.getElementById("newsletter")

  const strengthFill = document.getElementById("strengthFill")
  const strengthText = document.getElementById("strengthText")

  function setupPasswordToggle(toggleBtn, passwordField) {
    toggleBtn.addEventListener("click", () => {
      const isPassword = passwordField.type === "password"
      const eyeOpen = toggleBtn.querySelector(".eye-open")
      const eyeClosed = toggleBtn.querySelector(".eye-closed")

      if (isPassword) {
        passwordField.type = "text"
        eyeOpen.style.display = "none"
        eyeClosed.style.display = "block"
      } else {
        passwordField.type = "password"
        eyeOpen.style.display = "block"
        eyeClosed.style.display = "none"
      }
    })
  }

  setupPasswordToggle(passwordToggle, passwordInput)
  setupPasswordToggle(confirmPasswordToggle, confirmPasswordInput)

  function validateFullName(name) {
    return name.trim().length >= 2 && name.trim().length <= 50
  }

  function validateUsername(username) {
    const regex = /^[a-zA-Z0-9_]{3,20}$/
    return regex.test(username)
  }

  function validateEmail(email) {
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
    return regex.test(email)
  }

  function calculatePasswordStrength(password) {
    let score = 0
    const feedback = []

    if (password.length >= 8) score++
    else feedback.push("en az 8 karakter")

    if (/[A-Z]/.test(password)) score++
    else feedback.push("büyük harf")

    if (/[a-z]/.test(password)) score++
    else feedback.push("küçük harf")

    if (/\d/.test(password)) score++
    else feedback.push("sayı")

    if (/[!@#$%^&*(),.?":{}|<>]/.test(password)) score++
    else feedback.push("özel karakter")

    return { score, feedback }
  }

  function updatePasswordStrength(password) {
    const { score, feedback } = calculatePasswordStrength(password)

    strengthFill.className = "strength-fill"
    strengthText.className = "strength-text"

    if (password.length === 0) {
      strengthText.textContent = "Şifre gücü"
      return
    }

    let strength = ""
    let text = ""

    if (score <= 2) {
      strength = "weak"
      text = "Zayıf şifre"
    } else if (score === 3) {
      strength = "fair"
      text = "Orta şifre"
    } else if (score === 4) {
      strength = "good"
      text = "İyi şifre"
    } else {
      strength = "strong"
      text = "Güçlü şifre"
    }

    strengthFill.classList.add(strength)
    strengthText.classList.add(strength)
    strengthText.textContent = text

    if (feedback.length > 0 && score < 4) {
      strengthText.textContent += ` (ihtiyaç: ${feedback.join(", ")})`
    }
  }

  function validatePassword(password) {
    const { score } = calculatePasswordStrength(password)
    return password.length >= 8 && score >= 3
  }

  function showError(fieldId, message) {
    const errorElement = document.getElementById(`${fieldId}Error`)
    const inputElement = document.getElementById(fieldId)

    errorElement.textContent = message
    inputElement.style.borderColor = "#ef4444"

    setTimeout(() => {
      errorElement.textContent = ""
      inputElement.style.borderColor = ""
    }, 5000)
  }

  function clearErrors() {
    document.querySelectorAll(".field-error").forEach(el => el.textContent = "")
    document.querySelectorAll(".form-input").forEach(el => el.style.borderColor = "")
  }

  fullNameInput.addEventListener("blur", () => {
    if (!validateFullName(fullNameInput.value.trim())) {
      showError("fullName", "İsim 2-50 karakter arasında olmalı")
    }
  })

  usernameInput.addEventListener("blur", async () => {
    const username = usernameInput.value.trim()
    if (!validateUsername(username)) {
      showError("username", "Kullanıcı adı 3-20 karakter, harf, sayı ve _ içermeli")
    } else {
      if (!(await checkUsernameAvailability(username))) {
        showError("username", "Bu kullanıcı adı alınmış")
      }
    }
  })

  emailInput.addEventListener("blur", async () => {
    const email = emailInput.value.trim()
    if (!validateEmail(email)) {
      showError("email", "Geçerli bir e-posta girin")
    } else {
      if (!(await checkEmailAvailability(email))) {
        showError("email", "Bu e-posta ile zaten kayıt var")
      }
    }
  })

  passwordInput.addEventListener("input", () => {
    updatePasswordStrength(passwordInput.value)

    document.getElementById("passwordError").textContent = ""
    passwordInput.style.borderColor = ""

    if (confirmPasswordInput.value && passwordInput.value !== confirmPasswordInput.value) {
      showError("confirmPassword", "Şifreler eşleşmiyor")
    } else {
      document.getElementById("confirmPasswordError").textContent = ""
      confirmPasswordInput.style.borderColor = ""
    }
  })

  confirmPasswordInput.addEventListener("blur", () => {
    if (confirmPasswordInput.value && passwordInput.value !== confirmPasswordInput.value) {
      showError("confirmPassword", "Şifreler eşleşmiyor")
    }
  })

  ;[fullNameInput, usernameInput, emailInput, passwordInput, confirmPasswordInput].forEach(input => {
    input.addEventListener("input", () => {
      document.getElementById(`${input.id}Error`).textContent = ""
      input.style.borderColor = ""
    })
  })

  async function checkUsernameAvailability(username) {
    await new Promise(r => setTimeout(r, 500))
    const takenUsernames = ["admin", "user", "test", "demo", "john", "jane"]
    return !takenUsernames.includes(username.toLowerCase())
  }

  async function checkEmailAvailability(email) {
    await new Promise(r => setTimeout(r, 500))
    const takenEmails = ["admin@example.com", "user@example.com", "test@example.com"]
    return !takenEmails.includes(email.toLowerCase())
  }

  registerForm.addEventListener("submit", async e => {
    e.preventDefault()
    clearErrors()

    const fullName = fullNameInput.value.trim()
    const username = usernameInput.value.trim()
    const email = emailInput.value.trim()
    const password = passwordInput.value
    const confirmPassword = confirmPasswordInput.value
    const agreeTerms = agreeTermsCheckbox.checked
    const newsletter = newsletterCheckbox.checked

    let hasErrors = false

    if (!fullName) { showError("fullName","İsim gerekli"); hasErrors=true }
    else if (!validateFullName(fullName)) { showError("fullName","İsim 2-50 karakter arasında olmalı"); hasErrors=true }

    if (!username) { showError("username","Kullanıcı adı gerekli"); hasErrors=true }
    else if (!validateUsername(username)) { showError("username","Kullanıcı adı 3-20 karakter, harf, sayı ve _ içermeli"); hasErrors=true }

    if (!email) { showError("email","E-posta gerekli"); hasErrors=true }
    else if (!validateEmail(email)) { showError("email","Geçerli e-posta girin"); hasErrors=true }

    if (!password) { showError("password","Şifre gerekli"); hasErrors=true }
    else if (!validatePassword(password)) { showError("password","Şifre en az 8 karakter ve güçlü olmalı"); hasErrors=true }

    if (!confirmPassword) { showError("confirmPassword","Şifreyi onaylayın"); hasErrors=true }
    else if (password !== confirmPassword) { showError("confirmPassword","Şifreler eşleşmiyor"); hasErrors=true }

    if (!agreeTerms) { showError("agreeTerms","Kullanım şartlarını kabul etmelisiniz"); hasErrors=true }

    if (hasErrors) return

    registerButton.classList.add("loading")
    registerButton.disabled = true

    try {
      const [usernameAvailable, emailAvailable] = await Promise.all([
        checkUsernameAvailability(username),
        checkEmailAvailability(email)
      ])

      if (!usernameAvailable) { showError("username","Bu kullanıcı adı alınmış"); hasErrors=true }
      if (!emailAvailable) { showError("email","Bu e-posta ile zaten kayıt var"); hasErrors=true }
      if (hasErrors) return

      await new Promise(r => setTimeout(r,2000))

      const registrationData = { fullName, username, email, password, newsletter, timestamp:new Date().toISOString() }

      fetch("/api/register", {
        method:"POST",
        headers:{"Content-Type":"application/json"},
        body:JSON.stringify(registrationData)
      })
      .then(res=>{ if(!res.ok) throw new Error("Ağ hatası"); return res.json() })
      .then(data=>{
        console.log("Kayıt başarılı:",data)
        alert("Kayıt başarılı! Giriş yapabilirsiniz.")
        window.location.href="/login"
      })
      .catch(err=>{
        console.error("Kayıt hatası:",err)
        alert("Kayıt sırasında bir hata oluştu. Tekrar deneyin.")
      })

    } catch(err){
      console.error("Kayıt hatası:",err)
      alert("Kayıt sırasında bir hata oluştu. Tekrar deneyin.")
    } finally {
      registerButton.classList.remove("loading")
      registerButton.disabled=false
    }
  })

  document.querySelector(".google-btn").addEventListener("click",()=>{
    console.log("Google ile kayıt tıklandı")
    alert("Google ile kayıt burada yapılacak")
  })
  document.querySelector(".github-btn").addEventListener("click",()=>{
    console.log("GitHub ile kayıt tıklandı")
    alert("GitHub ile kayıt burada yapılacak")
  })

  document.querySelectorAll(".terms-link").forEach(link=>{
    link.addEventListener("click",e=>{
      e.preventDefault()
      alert(`${link.textContent} bir modal veya yeni sayfada açılır`)
    })
  })

  fullNameInput.focus()
})
