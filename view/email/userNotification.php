<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width"/>

    <style type="text/css">
    * {
      margin: 0;
      padding: 0;
      font-size: 100%;
      font-family: Helvetica, Arial, sans-serif;
      line-height: 1.65; }

    img {
      max-width: 100%;
      margin: 0 auto;
      display: block; }

    body, .body-wrap {
      width: 100% !important;
      height: 100%;
      background: #efefef;
      -webkit-font-smoothing: antialiased;
      -webkit-text-size-adjust: none; }

    a {
      color: #71bc37;
      text-decoration: none; }

    .text-center {
      text-align: center; }

    .text-right {
      text-align: right; }

    .text-left {
      text-align: left; }

    .button {
      display: inline-block;
      color: white;
      background: #71bc37;
      border: solid #71bc37;
      border-width: 10px 20px 8px;
      font-weight: bold;
      border-radius: 4px; }

    h1, h2, h3, h4, h5, h6 {
      margin-bottom: 20px;
      line-height: 1.25; }

    h1 {
      font-size: 32px; }

    h2 {
      font-size: 28px; }

    h3 {
      font-size: 24px; }

    h4 {
      font-size: 20px; }

    h5 {
      font-size: 16px; }

    p, ul, ol {
      font-size: 16px;
      font-weight: normal;
      margin-bottom: 10px; }

    .container {
      display: block;
      clear: both;
      margin: 0 auto;
      max-width: 580px; 
    }

    .container table {
      width: 100%;
      border-collapse: collapse; 
    }

    .container .masthead {
      padding: 25px 0;
      background: #367fa9;
      color: white; 
    }

    .container .masthead h1 {
      margin: 0 auto !important;
      max-width: 90%;
      text-transform: uppercase; }
    .container .content {
      background: white;
      padding: 30px 35px; 
    }
    .container .content.footer {
      background: none; 
    }
    .container .content.footer p {
      margin-bottom: 0;
      color: #888;
      text-align: center;
      font-size: 14px; 
    }
    .container .content.footer a {
      color: #888;
      text-decoration: none;
      font-weight: bold; 
    }
    .bold{
      font-weight: bold;
    }
    .title-text{
      margin-top: 20px;
      background-color: #2a2a2a;
      color: #fefefe;
      padding: 10px;
    }
    .list{
      padding-left: 20px;
    }
    .plan-container{
      border: 1px solid #dedede;
      padding: 10px;
    }
    .day-title{
      margin-top: 20px;
      font-weight: bold;
    }
    </style>
</head>
<body>
<table class="body-wrap">
    <tr>
        <td class="container">
            <!-- Message start -->
            <table>
                <tr>
                    <td align="center" class="masthead">
                        <h1><?php echo $title; ?></h1>
                    </td>
                </tr>
                <tr>
                    <td class="content">
                        <p class="dear-addressee">Dear <?php echo $user['first_name']; ?>,</p>
                        <p><?php echo $messageText; ?></p>
                        <div class="title-text bold"><?php echo $plan['plan_name']; ?></div>
                        <div class="plan-container">
                          <div><?php echo $plan['plan_description']; ?></div>
                          <?php if (isset($plan['days']) && $plan['days']) { ?>
                            <?php foreach ($plan['days'] as $day) { ?>
                              <div class="day-title"><?php echo $day['day_name']; ?></div>
                              <?php if ($day['exercises']) { ?>
                                <ul class="list">
                                  <?php foreach ($day['exercises'] as $exercise) { ?>
                                    <li><?php echo $exercise['name']; ?> - <?php echo sprintf('%02d h %02d min %02d sec', ($exercise['exercise_duration']/3600),($exercise['exercise_duration']/60%60), $exercise['exercise_duration']%60) ; ?></li>
                                  <?php } ?>
                                </ul>
                              <?php } ?>
                            <?php } ?>
                          <?php } ?>
                        </div>
                        <br>
                        <p>Kind regards,<br>
                        Team of the website</p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td class="container">
            <!-- Message start -->
            <table>
                <tr>
                    <td class="content footer" align="center">
                        <p><a href="<?php echo WEB_ROOT; ?>">Our website</a></p>
                        <p><a href="mailto:<?php echo EMAIL_INFO; ?>"><?php echo EMAIL_INFO; ?></a></p>
                        <p><a href="tel:+36702926150">+36 70 2926150</a></p>
                    </td>
                </tr>
            </table>

        </td>
    </tr>
</table>
</body>
</html>