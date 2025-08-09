      <?php
if (!isset($_SERVER['HTTP_REFERER']) || strpos($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST']) === false) {
    header("HTTP/1.1 403 Forbidden");
    header("location:../error.php");
    exit();
}

// Sinon, afficher le CSS normalement
header("Content-Type: text/css");
?>

      
      .dashboard-container {
            max-width: 100%;
            margin: 0 auto;
        }

        .header {
            background: var(--secondary-color);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .header h1 {
            color:var( --text-color);
            font-size: 2.5em;
            margin-bottom: 10px;
            background: linear-gradient(45deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .header p {
            color: var( --text-color);
            font-size: 1.1em;
        }

        .cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 30px;
            margin-bottom: 30px;
        }

        .card {
            background: var(--secondary-color);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
        }

        .card-header {
            display: flex;
            align-items: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f0f0;
        }


        /* satrt label */
        .label-upload {
    display: inline-block;
    padding: 10px 20px;
    text-align:center;
    background-color: green;
    background: var(--text-light) ;
    color: var( --secondary-color);
    border-radius: 8px;
    cursor: pointer;
    border: 1px dashed var(--text-color);
    font-weight: bold;
    transition: background-color 0.3s ease;
}

.label-upload:hover {
    background: var(--text-lightHover) ;
}

.label-upload i {
    margin-right: 8px;
}
        /* end label */

        .card-icon {
            width: 60px;
            height: 60px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: var( --text-color);
            margin-right: 20px;
        }

        .card-icon.stats { background: linear-gradient(45deg, #ff6b6b, #ee5a52); }
        .card-icon.employee { background: linear-gradient(45deg, #4ecdc4, #44a08d); }
        .card-icon.signature { background: linear-gradient(45deg, #45b7d1, #96c93d); }

        .card-title {
            font-size: 1.4em;
            color: var( --text-color);
            font-weight: 600;
        }

        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 15px;
            margin-top: 20px;
        }

        .stat-item {
            text-align: center;
            padding: 15px;
            background: linear-gradient(45deg, #667eea, #764ba2);
            border-radius: 15px;
            color: var( --text-color);
            transition: transform 0.3s ease;
        }

        .stat-item:hover {
            transform: scale(1.05);
        }

        .stat-number {
            font-size: 2em;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 0.9em;
            opacity: 0.9;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var( --secondary-color);
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e1e1e1;
            border-radius: 10px;
            font-size: 16px;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.8);
        }

        .form-control:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .btn {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: var( --text-color);
            border: none;
            padding: 12px 30px;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
        }

        .btn:active {
            transform: translateY(0);
        }

        .upload-area {
            border: 2px dashed #667eea;
            border-radius: 15px;
            padding: 40px;
            text-align: center;
            background: rgba(102, 126, 234, 0.05);
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .upload-area:hover {
            background: rgba(102, 126, 234, 0.1);
            border-color: #764ba2;
        }

        .upload-area.dragover {
            background: rgba(102, 126, 234, 0.15);
            border-color: #764ba2;
            transform: scale(1.02);
        }

        .upload-icon {
            font-size: 48px;
            color: #667eea;
            margin-bottom: 15px;
        }

        .preview-container {
            margin-top: 20px;
            text-align: center;
        }

        .preview-image {
            max-width: 200px;
            max-height: 150px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .success-message {
            background: linear-gradient(45deg, #2ecc71, #27ae60);
            color: var( --text-color);
            padding: 15px;
            border-radius: 10px;
            margin-top: 15px;
            text-align: center;
            display: none;
        }

        .error-message {
            background: linear-gradient(45deg, #e74c3c, #c0392b);
            color: var( --text-color);
            padding: 15px;
            border-radius: 10px;
            margin-top: 15px;
            text-align: center;
            display: none;
        }

        @media (max-width: 768px) {
            .cards-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }
            
            .header h1 {
                font-size: 2em;
            }
            
            .card {
                padding: 20px;
            }
        }

