<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/favicon.ico" type="image/x-icon">
    <title>Homie - Symfony-based PHP Framework for creating homepage</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            line-height: 1.6;
            max-width: 860px;
            margin: 0 auto;
            padding: 20px;
            color: #24292e;
            background: #f6f8fa;
        }
        header {
            padding: 2rem 0;
            border-bottom: 1px solid #e1e4e8;
        }
        h1 {
            font-size: 2rem;
            font-weight: 600;
            color: #24292e;
            margin: 0;
        }
        .tagline {
            color: #586069;
            font-size: 1.2rem;
            margin-top: 0.5rem;
        }
        .readme {
            background: white;
            padding: 2rem;
            border: 1px solid #e1e4e8;
            border-radius: 6px;
            margin: 2rem 0;
        }
        .highlights {
            margin: 2rem 0;
        }
        .highlight-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }
        .highlight {
            background: #fff;
            padding: 1rem;
            border: 1px solid #e1e4e8;
            border-radius: 6px;
        }
        .highlight h3 {
            color: #0366d6;
            margin-top: 0;
        }
        .cta {
            margin: 2rem 0;
        }
        .btn {
            display: inline-block;
            padding: 0.5rem 1rem;
            background: #2ea44f;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 500;
            transition: background 0.3s;
        }
        .btn:hover {
            background: #22863a;
        }
        footer {
            margin-top: 3rem;
            color: #586069;
            font-size: 0.9rem;
            text-align: center;
        }
        .github-mark {
            width: 24px;
            vertical-align: middle;
            margin-right: 0.5rem;
        }
    </style>
</head>
<body>
    <?=$this->section('navbar')?>
    <?=$this->section('content')?>
    <?=$this->section('footer')?>
</body>
</html>