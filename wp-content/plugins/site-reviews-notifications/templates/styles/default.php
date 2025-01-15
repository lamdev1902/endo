<?php
    // !important; is a gmail hack to prevent styles being stripped if it doesn't like something.
    // body { padding:0 } ensures proper scale/positioning of the email in the iOS native email app.
    // @see https://templates.mailchimp.com/development/css/client-specific-styles/
?>

.email {
    -webkit-text-size-adjust:none !important;
    background-color:{{ background_color }};
    color:{{ body_text_color }};
    height:100%;
    line-height:1.6;
    margin-bottom:0;
    margin-left:0;
    margin-right:0;
    margin-top:0;
    padding: 0;
    width:100% !important;
}
.email-wrapper {
    background-color:{{ background_color }};
    border-collapse:collapse;
    margin-bottom:0;
    margin-left:0;
    margin-right:0;
    margin-top:0;
    mso-table-lspace:0pt;
    mso-table-rspace:0pt;
    padding-bottom:0;
    padding-left:0;
    padding-right:0;
    padding-top:0;
    width:100%;
}
.email-wrapper_inner {
    padding-top:10px;
    padding-bottom:10px;
    padding-left:10px;
    padding-right:10px;
    text-align:center;
}
.email-content {
    border-collapse:collapse;
    margin-bottom:0;
    margin-left:0;
    margin-right:0;
    margin-top:0;
    mso-table-lspace:0pt;
    mso-table-rspace:0pt;
    padding-bottom:0;
    padding-left:0;
    padding-right:0;
    padding-top:0;
    width:100%;
}
.email-top {
    border-collapse:collapse;
    margin-bottom:0;
    margin-left:0;
    margin-right:0;
    margin-top:0;
    mso-table-lspace:0pt;
    mso-table-rspace:0pt;
    padding-bottom:0;
    padding-left:0;
    padding-right:0;
    padding-top:10px;
    width:100%;
}
.email-top_logo {
    text-align:center;
}
.email-top_logo img {
    max-height: 64px;
    max-width: 150px;
    padding-bottom:20px;
    padding-top:20px;
}
.email-body {
    background-color:{{ body_background_color }};
    border-collapse:collapse;
    max-width:640px;
    mso-table-lspace:0pt;
    mso-table-rspace:0pt;
    text-align:center;
    width:100%;
}
.email-body_header {
    background-color:{{ brand_color }};
    font-family:Merriweather, Charter, Georgia, serif;
    font-size:0;
    padding-bottom:30px;
    padding-left:50px;
    padding-right:50px;
    padding-top:40px;
    text-align:center;
}
.email-body_header h1 {
    color:{{ header_text_color }};
    font-size:21px;
    font-weight:normal;
    margin-bottom:0px;
    margin-left:0;
    margin-right:0;
    margin-top:0px;
    padding:0;
    text-align:center;
}
.email-body_message {
    background-color:{{ body_background_color }};
    font-family:Merriweather, Charter, Georgia, serif;
    padding-bottom:20px;
    padding-left:50px;
    padding-right:50px;
    padding-top:45px;
}
.email-body_message img {
    border-style: none;
    height: auto;
    margin:0;
    max-width: 100%;
    padding:0;
}
.email-body_message h2 {
    color:{{ body_text_color }};
    font-family:Merriweather, Charter, Georgia, serif;
    font-size:21px;
    font-weight:bold;
    margin-top:0;
    margin-left:0;
    margin-right:0;
    margin-bottom:25px;
    padding:0;
    text-align:<?= is_rtl() ? 'right' : 'left'; ?>;
}
.email-body_message h3 {
    color:{{ body_text_color }};
    font-family:Merriweather, Charter, Georgia, serif;
    font-size:18px;
    font-weight:bold;
    margin-top:0;
    margin-left:0;
    margin-right:0;
    margin-bottom:25px;
    padding:0;
    text-align:<?= is_rtl() ? 'right' : 'left'; ?>;
}
.email-body_message p {
    color:{{ body_text_color }};
    font-family:Merriweather, Charter, Georgia, serif;
    font-size:16px;
    line-height:25px;
    margin-bottom:25px;
    margin-top:0;
    text-align:<?= is_rtl() ? 'right' : 'left'; ?>;
}
.email-body_message a:not(.btn) {
    font-family:Merriweather, Charter, Georgia, serif;
    color:{{ body_link_color }};
    text-decoration:underline;
}
.email-body_message a.btn {
    -webkit-text-size-adjust:none;
    background-color:{{ brand_color }};
    border-bottom-color:{{ brand_color }};
    border-bottom-style:solid;
    border-bottom-width:10px;
    border-left-color:{{ brand_color }};
    border-left-style:solid;
    border-left-width:18px;
    border-radius:3px;
    border-right-color:{{ brand_color }};
    border-right-style:solid;
    border-right-width:18px;
    border-top-color:{{ brand_color }};
    border-top-style:solid;
    border-top-width:10px;
    color:{{ header_text_color }};
    display:inline-block;
    font-family:Merriweather, Charter, Georgia, serif;
    font-size:14px;
    height:auto;
    text-align:center;
    text-decoration:none;
}
.email-body_message ol {
    font-family:Merriweather, Charter, Georgia, serif;
    font-size:16px;
    line-height:1.6;
    text-align:<?= is_rtl() ? 'right' : 'left'; ?>;
}
.email-body_message ul {
    font-family:Merriweather, Charter, Georgia, serif;
    font-size:16px;
    line-height:1.6;
    text-align:<?= is_rtl() ? 'right' : 'left'; ?>;
}
.email-body_message li {
    color:{{ body_text_color }};
    font-family:Merriweather, Charter, Georgia, serif;
    font-size:16px;
    line-height:1.6;
    text-align:<?= is_rtl() ? 'right' : 'left'; ?>;
}
.email-body_footer {
    background-color:{{ background_color }};
    border-top-color:{{ brand_color }};
    border-top-style:solid;
    border-top-width:6px;
    font-family:Merriweather, Charter, Georgia, serif;
    padding-bottom:16px;
    padding-left:50px;
    padding-right:50px;
    padding-top:16px;
}
.email-body_footer p {
    color:{{ footer_text_color }};
    font-size:14px;
    line-height:25px;
    margin-bottom:0;
    margin-left:0;
    margin-right:0;
    margin-top:0;
    text-align:center;
}
.email-body_footer a {
    color:{{ footer_text_color }};
    text-decoration:underline;
}
