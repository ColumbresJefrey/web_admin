document.addEventListener("DOMContentLoaded", function () {
    const inputs = document.querySelectorAll(".otp-inputs input");
    const nextButton = document.querySelector(".next-btn");
    
    inputs.forEach((input, index) => {
        input.addEventListener("input", (e) => {
            if (e.target.value.length === 1 && index < inputs.length - 1) {
                inputs[index + 1].focus();
            }
        });
        
        input.addEventListener("keydown", (e) => {
            if (e.key === "Backspace" && index > 0 && e.target.value === "") {
                inputs[index - 1].focus();
            }
        });
    });
    
    nextButton.addEventListener("click", () => {
        let otpCode = "";
        inputs.forEach(input => {
            otpCode += input.value;
        });
        
        if (otpCode.length === inputs.length) {
            alert("OTP Verified: " + otpCode);
        } else {
            alert("Please enter the complete OTP.");
        }
    });
});
