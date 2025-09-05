document.addEventListener("DOMContentLoaded", () => {

    const tabs = document.querySelectorAll(".captcha-tab")
    const contents = document.querySelectorAll(".captcha-content")

    tabs.forEach((tab) => {
        tab.addEventListener("click", () => {
            const targetTab = tab.dataset.tab

            tabs.forEach((t) => t.classList.remove("active"))
            contents.forEach((c) => c.classList.remove("active"))

            tab.classList.add("active")
            document.getElementById(`${targetTab}Captcha`).classList.add("active")

            resetCaptchaState()
        })
    })

    const captchaSuccess = document.getElementById("captchaSuccess")
    const captchaError = document.getElementById("captchaError")
    const continueButton = document.getElementById("continueButton")

    function resetCaptchaState() {
        captchaSuccess.style.display = "none"
        captchaError.style.display = "none"
        contents.forEach((content) => {
            if (content.classList.contains("active")) {
                content.style.display = "block"
            }
        })
    }

    continueButton.addEventListener("click", () => {

        alert("Devam ediliyor...")
        window.location.href = "index.html"
    })

    document.addEventListener("keydown", (e) => {
        if (e.key === "Escape") {
            resetCaptchaState()
        }
    })

    const mathInput = document.getElementById("mathInput")
    document.querySelector('[data-tab="math"]').addEventListener("click", () => {
        setTimeout(() => mathInput && mathInput.focus(), 100)
    })
})

function submitForm() {
    document.getElementById("captchaSuccess").style.display = "block";
    setInterval(() => {
        document.getElementById('captchaForm').submit();
    }, 1000);
}