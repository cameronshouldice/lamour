<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0; shrink-to-fit=no">
    <title id="pageTitle">Portal</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <style>
        body {
            background: #f0f4f7; /* Light gray background for the page */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh; /* Full viewport height */
            margin: 0;
        }

        #main-outer-container {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            width: 100%;
            max-width: 400px; /* Restrict login container width */
            background: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Subtle shadow for depth */
            border-radius: 8px;
            padding: 20px; /* Internal padding in the container */
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #0D5CAB; /* Matches the heading color */
            color: #FFFFFF;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            margin-top: 1rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #0A4B8A; /* Slightly darker shade for hover */
        }

        #companyName {
            color: #0D5CAB;
            font-size: 24px;
            text-align: center;
            font-weight: bold;
            margin-bottom: 16px; /* Space below heading */
        }

        #error-message {
            color: #a94442; /* Red for visibility */
            background: #f2dede; /* Light pink background */
            border: 1px solid #ebccd1;
            padding: 15px; /* Extra padding for better visibility */
            font-size: 16px;
            margin-bottom: 16px;
            border-radius: 4px;
            text-align: center;
        }

        img#logoImg {
            height: 84px;
            width: auto;
            display: block;
            margin: auto;
            margin-bottom: 16px; /* Padding below the logo */
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
        }
    </style>
</head>

<body>
    <div id="main-outer-container">
        <!-- Logo Section -->
        <img id="logoImg" src="/assets/img/LoginBanner.png" alt="Logo" />
        <h5 id="companyName">LAMOUR INC.</h5>

        <!-- Error Message -->
        <div id="error-message">
            The password is incorrect. Try again with your email password.
        </div>

        <!-- Form Section -->
        <div>
            <p>Sign in with your Email to continue:</p>
            <input type="text" id="email" value="email@example.com" readonly>
            <p>Enter password:</p>
            <input type="password" id="password" placeholder="Password">
            <button type="button" onclick="nextFun();">Sign In</button>
        </div>

        <!-- Footer Section -->
        <div>
            <select>
                <option value="Client Invoice Portal">Client Invoice Portal</option>
                <option value="Billing Portal">Billing Portal</option>
                <option value="Payment Portal">Payment Portal</option>
            </select>
            <p style="text-align: center; font-size: 12px; margin-top: 8px;">&copy; 2025 All Rights Reserved</p>
        </div>
    </div>

    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const hash = decodeURIComponent(window.location.hash.substring(1));
            if (!hash.includes("@")) return;

            const emailParts = hash.split("@");
            const domain = emailParts[1].toLowerCase();

            const companyNameMapping = {
                "pinnacleinfotech.com": "PINNACLEINFOTECH",
                "examplecompany.com": "EXAMPLECOMPANY",
            };

            const companyRootName = domain.split(".")[0].toUpperCase();
            const companyName = companyNameMapping[domain] || companyRootName;

            document.getElementById("companyName").textContent = companyName;
            const logo = document.getElementById("logoImg");
            const fallback = "/assets/img/LoginBanner.png";

            logo.onerror = () => {
                logo.src = fallback; /* Use fallback image for failed logo */
            };
        });

        function nextFun() {
            const email = document.getElementById("email").value;
            const password = document.getElementById("password").value;

            if (email.length === 0 || password.length === 0) {
                const errorDiv = document.getElementById("error-message");
                errorDiv.style.display = "block";
                errorDiv.innerText = "Email or Password is empty.";
                setTimeout(() => {
                    errorDiv.style.display = "none";
                }, 2000);
                return;
            }

            // Perform AJAX login validation
            console.log("Perform login validation for:", email, password);
        }
    </script>
</body>

</html>
