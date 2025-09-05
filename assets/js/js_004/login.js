document.addEventListener("DOMContentLoaded", () => {
    const loginForm = document.getElementById("loginForm")
    const usernameInput = document.getElementById("username")
    const passwordInput = document.getElementById("password")
    const passwordToggle = document.getElementById("passwordToggle")
    const loginButton = document.getElementById("loginButton")
    const rememberMeCheckbox = document.getElementById("rememberMe")

    passwordToggle.addEventListener("click", () => {
        const isPassword = passwordInput.type === "password"
        const eyeOpen = passwordToggle.querySelector(".eye-open")
        const eyeClosed = passwordToggle.querySelector(".eye-closed")

        if (isPassword) {
            passwordInput.type = "text"
            eyeOpen.style.display = "none"
            eyeClosed.style.display = "block"
        } else {
            passwordInput.type = "password"
            eyeOpen.style.display = "block"
            eyeClosed.style.display = "none"
        }
    })

    function validateUsername(username) {
        return username.length >= 3
    }

    function validatePassword(password) {
        return password.length >= 3
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
        const errorElements = document.querySelectorAll(".field-error")
        const inputElements = document.querySelectorAll(".form-input")

        errorElements.forEach((el) => (el.textContent = ""))
        inputElements.forEach((el) => (el.style.borderColor = ""))
    }

    usernameInput.addEventListener("blur", () => {
        const username = usernameInput.value.trim()
        if (username && !validateUsername(username)) {
            showError("username", "Username must be at least 3 characters long")
        }
    })

    passwordInput.addEventListener("blur", () => {
        const password = passwordInput.value
        if (password && !validatePassword(password)) {
            showError("password", "Password must be at least 6 characters long")
        }
    })

    usernameInput.addEventListener("input", () => {
        document.getElementById("usernameError").textContent = ""
        usernameInput.style.borderColor = ""
    })

    passwordInput.addEventListener("input", () => {
        document.getElementById("passwordError").textContent = ""
        passwordInput.style.borderColor = ""
    })

    $(document).ready(function() {
        $('#loginForm').on('submit', function(e) {
            e.preventDefault()
            clearErrors()

            const username = $('#username').val().trim()
            const password = $('#password').val()
            const rememberMe = $('#rememberMe').is(":checked")

            let hasErrors = false

            if (!username) {
                showError("username", "Username is required")
                hasErrors = true
            } else if (!validateUsername(username)) {
                showError("username", "Username must be at least 3 characters long")
                hasErrors = true
            }

            if (!password) {
                showError("password", "Password is required")
                hasErrors = true
            } else if (!validatePassword(password)) {
                showError("password", "Password must be at least 6 characters long")
                hasErrors = true
            }

            if (hasErrors) {
                return
            }

            loginButton.classList.add("loading")
            loginButton.disabled = true

            $.ajax({
                type: 'POST',
                url: '/api/loginUser',
                data: {
                    username: username,
                    remember_me: rememberMe,
                    password: password
                },
                success: function(response) {
                    if (response.status) {
                        if (rememberMe) {
                            localStorage.setItem("rememberedUsername", username)
                        }
                        sessionStorage.setItem("isLoggedIn", "true")
                        sessionStorage.setItem("username", username)

                        Swal.fire({
                            icon: 'success',
                            title: 'Başarılı',
                            text: 'Giriş başarılı.',
                        }).then(() => {
                            window.location.href = '/'
                        })
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Hata',
                            text: response.message || 'Giriş başarısız.',
                        })
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Hata',
                        text: 'Sunucu hatası, lütfen tekrar deneyin.',
                    })
                },
                complete: function() {
                    loginButton.classList.remove("loading")
                    loginButton.disabled = false
                }
            })
        })
    })

    const rememberedUsername = localStorage.getItem("rememberedUsername")
    if (rememberedUsername) {
        usernameInput.value = rememberedUsername
        rememberMeCheckbox.checked = true
    }

    const forgotPasswordLink = document.querySelector(".forgot-password")
    forgotPasswordLink.addEventListener("click", (e) => {
        e.preventDefault()
        const username = usernameInput.value.trim()

        if (username && validateUsername(username)) {
            alert(`Password reset process would be initiated for user: ${username}`)
        } else {
            alert("Please enter a valid username first")
            usernameInput.focus()
        }
    })

    if (!usernameInput.value) {
        usernameInput.focus()
    } else if (!passwordInput.value) {
        passwordInput.focus()
    }
})