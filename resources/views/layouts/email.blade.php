<html>

<head>
    <meta name="viewport" content="width=device-width">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Scientific Platform</title>
    <style>
        /* ---------------------------------------------------
        RESPONSIVENESS
        ------------------------------------------------------ */
        /* Set a max-width, and make it display as block so it will automatically stretch to that width, but will also shrink down on a phone or something */
        .container {
            clear: both !important;
            display: block !important;
            Margin: 0 auto !important;
            max-width: 600px !important;
        }

        /* Set the padding on the td rather than the div for Outlook compatibility */
        .body-wrap .container {
            padding: 0;
        }

        /* This should also be a block element, so that it will fill 100% of the .container */
        .content {
            display: block;
            margin: 0 auto;
            max-width: 600px;
        }
    </style>
</head>

<body bgcolor="#f6f6f6" style="font-family: 'Montserrat', sans-serif;font-weight:200;">
    <!-- body -->
    <table class="body-wrap" bgcolor="#f6f6f6" style="width:100%">
        <tr>
            <td></td>
            <td class="container" bgcolor="#FFFFFF">
                <!-- content -->
                <p class="content" style="max-width:600px;display:block;">
                    <table width="600" cellpadding="0" cellspacing="0" bgcolor="#ffffff">
                        <tr>
                            <td><img src="https://nucleusglobal-cdn.s3.amazonaws.com/nucleus-smp/images/nucleus-header.png" width="700" height="176"></td>
                        </tr>
                        <tr>
                            <td>
                                <table cellpadding="20" cellspacing="0">
                                    <tr>

                                        <td bgcolor="#363636" color="#ffffff" style="vertical-align: middle;">
                                            <h1 style="font-family: 'Montserrat', sans-serif; line-height: 1.5em;color:#ffffff;margin:0;font-weight: 200;font-size: 1.7em;">
                                                Scientific Messenging Platform and Lexicon
                                            </h1>
                                        </td>

                                    </tr>
                                    <tr>

                                        <td style="font-family: 'Montserrat', sans-serif; line-height: 1.6em;font-weight:200;">


                                            {{-- INSERT CONTENT HERE --}}

                                                @yield("content")

                                            {{-- END OF INSERT CONTENT HERE --}}

                                            <br /><br />
                                            Kind Regards,<br />
                                            <em><b>The Scientific Messenging Platform and Lexicon team</b></em><br /><br />


                                        </td>

                                    </tr>
                                </table>
                            </td>

                        </tr>
                    </table>
                    </div>
                    <!-- /content -->
            </td>
            <td></td>
        </tr>
    </table>
    <!-- /body -->
    <!-- /footer -->
</body>

</html>
