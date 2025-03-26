<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Homie ?></title>
    <style>
        /* Reset styles */
        body {
            margin: 0;
            padding: 0;
            min-width: 100%;
            font-family: Arial, sans-serif;
            font-size: 16px;
            line-height: 1.5;
            color: #333333;
        }
        /* Container */
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
        }
        /* Header */
        .header {
            text-align: center;
            padding: 20px 0;
            background-color: #f8f9fa;
        }
        .logo {
            max-width: 150px;
            height: auto;
        }
        /* Content */
        .content {
            padding: 30px 20px;
        }
        .welcome-text {
            font-size: 24px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 20px;
        }
        .message {
            margin-bottom: 30px;
        }
        /* Button */
        .button {
            display: inline-block;
            padding: 12px 24px;
            background-color: #3498db;
            color: #ffffff;
            text-decoration: none;
            border-radius: 4px;
            margin: 20px 0;
        }
        /* Footer */
        .footer {
            text-align: center;
            padding: 20px;
            background-color: #f8f9fa;
            font-size: 14px;
            color: #666666;
        }
        .social-links {
            margin: 20px 0;
        }
        .social-links a {
            color: #3498db;
            text-decoration: none;
            margin: 0 10px;
        }
    </style>
</head>
<body>
    <div class="container"> 
        <?=$this->section('header')?>
        <?=$this->section('content')?>
        <?=$this->section('footer')?>
    </div>
</body>
</html> 