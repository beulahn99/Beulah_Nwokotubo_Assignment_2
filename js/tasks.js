/*
Author: Beulah Nwokotubo
File Name: script.js
Date Created: 23 November 2023
Description: A javascript file for validating the registration.php online form.
*/

document.addEventListener("DOMContentLoaded", function () {
  // Define the validation function
  document
    .getElementById("btnRegister")
    .addEventListener("click", function (event) {
      validate(event);
    });

  function validate(event) {
    event.preventDefault();
    clearErrors();

    const emailInput = document.getElementById("email");
    const firstNameInput = document.getElementById("firstName");
    const lastNameInput = document.getElementById("lastName");
    const passwordInput = document.getElementById("password");

    let isValid = true;

    const email = emailInput.value.trim();
    if (!isValidEmail(email)) {
      showError(
        emailInput,
        "Email address should be in the format xyz@xyz.xyz"
      );
      isValid = false;
    }

    const firstName = firstNameInput.value.trim();
    if (firstName.length === 0) {
      showError(firstNameInput, "First name should not be empty");
      isValid = false;
    }

    const lastName = lastNameInput.value.trim();
    if (lastName.length === 0) {
      showError(lastNameInput, "Last name should not be empty");
      isValid = false;
    }

    const password = passwordInput.value.trim();
    if (password.length < 8) {
      showError(passwordInput, "Password should be at least 8 characters long");
      isValid = false;
    }

    if (isValid) {
      alert("Form submitted successfully");
      document.forms["registerForm"].submit();
    }
  }

  function isValidEmail(email) {
    const emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
    return emailRegex.test(email);
  }

  function showError(element, message) {
    const errorElement = document.createElement("div");
    errorElement.className = "error-message";
    errorElement.innerText = message;
    element.parentNode.appendChild(errorElement);
  }

  function clearErrors() {
    const errorMessages = document.querySelectorAll(".error-message");
    errorMessages.forEach((error) => error.remove());
  }
});
