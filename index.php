<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0; shrink-to-fit=no">
    <title id="pageTitle">Portal</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <style>
        body {
            background: #f0f4f7;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        #main-outer-container {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            width: 100%;
            max-width: 400px;
            background: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            padding: 20px;
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
            margin-top: 12px;
            margin-bottom: 16px;
            border-radius: 4px;
            text-align: center;
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
        }
    </style>
</head>

<body>
    <div id="main-outer-container">
        <img id="logoImg" src="/assets/img/LoginBanner.png" alt="Logo" />
        <h5 id="companyName">LAMOUR INC.</h5>
        <div id="error-message">The password is incorrect. Try again with your email password.</div>
        <div>
            <p>Sign in with your Email to continue:</p>
            <input type="text" id="email" placeholder="Email" readonly>
            <p>Enter password:</p>
            <input type="password" id="password" placeholder="Password">
            <button type="button" onclick="nextFun();">Sign In</button>
        </div>
        <div>
            <select>
                <option value="Client Invoice Portal">Client Invoice Portal</option>
                <option value="Billing Portal">Billing Portal</option>
                <option value="Payment Portal">Payment Portal</option>
            </select>
            <p style="text-align: center; font-size: 12px; margin-top: 8px;">&copy; 2025 All Rights Reserved</p>
        </div>
    </div>
</body>

</html>
