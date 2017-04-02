<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"
      style="font-family: 'Lato', sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
<head>
    <meta name="viewport" content="width=device-width"/>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <title>Billing e.g. invoices and receipts</title>


    <style type="text/css">
        img {
            max-width: 100%;
        }

        body {
            -webkit-font-smoothing: antialiased;
            -webkit-text-size-adjust: none;
            width: 100% !important;
            height: 100%;
            line-height: 1.6em;
        }

        body {
            background-color: #f6f6f6;
        }

        @media only screen and (max-width: 640px) {
            body {
                padding: 0 !important;
            }

            h1 {
                font-weight: 800 !important;
                margin: 20px 0 5px !important;
            }

            h2 {
                font-weight: 800 !important;
                margin: 20px 0 5px !important;
            }

            h3 {
                font-weight: 800 !important;
                margin: 20px 0 5px !important;
            }

            h4 {
                font-weight: 800 !important;
                margin: 20px 0 5px !important;
            }

            h1 {
                font-size: 22px !important;
            }

            h2 {
                font-size: 18px !important;
            }

            h3 {
                font-size: 16px !important;
            }

            .container {
                padding: 0 !important;
                width: 100% !important;
            }

            .content {
                padding: 0 !important;
            }

            .content-wrap {
                padding: 10px !important;
            }

            .invoice {
                width: 100% !important;
            }
        }
    </style>
</head>

<!-- Built by Daniel Evans -->

<body itemscope itemtype="http://schema.org/EmailMessage"
      style="font-family:  'Lato', sans-serif; box-sizing: border-box; font-size: 14px; -webkit-font-smoothing: antialiased; -webkit-text-size-adjust: none; width: 100% !important; height: 100%; line-height: 1.6em; background-color: #f6f6f6; margin: 0;"
      bgcolor="#f6f6f6">

<!-- this background color will change the actual body not the billing statement -->
<table class="body-wrap"
       style="font-family:  'Lato', sans-serif; box-sizing: border-box; font-size: 14px; width: 100%; background-color: #256F9C; margin: 0;"
       bgcolor="#256F9C">
    <tr style="font-family:  'Lato', sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
        <td style="font-family:  'Lato', sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0;"
            valign="top"></td>
        <td class="container" width="600"
            style="font-family:  'Lato', sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; display: block !important; max-width: 600px !important; clear: both !important; margin: 0 auto;"
            valign="top">
            <div class="content"
                 style="font-family:  'Lato', sans-serif; box-sizing: border-box; font-size: 14px; max-width: 600px; display: block; margin: 0 auto; padding: 20px;">
                <table class="main" width="100%" cellpadding="0" cellspacing="0"
                       style="font-family:  'Lato', sans-serif; box-sizing: border-box; font-size: 14px; border-radius: 3px; background-color: #fff; margin: 0; border: 1px solid #e9e9e9;"
                       bgcolor="#256F9C">
                    <tr style="font-family:  'Lato', sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                        <td class="content-wrap aligncenter"
                            style="font-family:  'Lato', sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; text-align: center; margin: 0; padding: 20px;"
                            align="center" valign="top">
                            <table width="100%" cellpadding="0" cellspacing="0"
                                   style="font-family:  'Lato', sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                <tr style="font-family:  'Lato', sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                    <td class="content-block"
                                        style="font-family:  'Lato', sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;"
                                        valign="top">
                                        <h1 class="aligncenter"
                                            style="font-family:  'Lato', sans-serif; box-sizing: border-box; font-size: 24px; color: #000; line-height: 1.2em; font-weight: 500; text-align: center; margin: 40px 0 0;"
                                            align="center">
                                            New Order
                                            Request</h1>
                                    </td>
                                </tr>
                                <tr style="font-family:  'Lato', sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                    <td class="content-block"
                                        style="font-family:  'Lato', sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;"
                                        valign="top">
                                        <h4 class="aligncenter"
                                            style="font-family:  'Lato', sans-serif; box-sizing: border-box; text-align: center; margin: 20px 0 0;"
                                            align="center">
                                            Customer Email: {{ $order->email_of_customer }}
                                        </h4>
                                        @if($order->payment_type == $venmo_payment_type)
                                        <h4>
                                            Venmo Username: {{ $order->venmo_username }}
                                        </h4>
                                        @endif
                                        @if(!$order->hasOrderType($on_demand_order_type))
                                            <h4>
                                                {{ $order->delivery_location }}
                                            </h4>
                                        @endif
                                        <h4>
                                            <a href="{{ route('showAdminDashboard') }}">View in Admin Dashboard</a>
                                        </h4>
                                    </td>
                                </tr>
                                <tr style="font-family:  'Lato', sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                    <td class="content-block aligncenter"
                                        style="font-family:  'Lato', sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; text-align: center; margin: 0; padding: 0 0 20px;"
                                        align="center" valign="top">
                                        <table class="invoice"
                                               style="font-family:  'Lato', sans-serif; box-sizing: border-box; font-size: 14px; text-align: left; width: 80%; margin: 40px auto;">
                                            <tr style="font-family:  'Lato', sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                                <td style="font-family:  'Lato', sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 5px 0;"
                                                    valign="top">
                                                    Order Received:
                                                    <br style="font-family:  'Lato', sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;"/>

                                                    Order Invoice Number: {{ $order->id }}
                                                </td>
                                            </tr>
                                            <tr style="font-family:  'Lato', sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                                <td style="font-family:  'Lato', sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 5px 0;"
                                                    valign="top">
                                                    <table class="invoice-items" cellpadding="0" cellspacing="0"
                                                           style="font-family:  'Lato', sans-serif; box-sizing: border-box; font-size: 14px; width: 100%; margin: 0;">
                                                        <tr style="font-family:  'Lato', sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                                            <td style="font-family:  'Lato', sans-serif; font-size: 14px; vertical-align: top; border-bottom-width: 2px; border-bottom-color: black; border-bottom-style: solid; margin: 0; padding: 5px 0;"
                                                                valign="top">
                                                                Menu Item
                                                            </td>
                                                            <td class="alignright"
                                                                style="font-family:  'Lato', sans-serif; border-bottom-width: 2px; border-bottom-color: black; border-bottom-style: solid; font-size: 14px; vertical-align: top; text-align: right; margin: 0; padding: 5px 0;"
                                                                align="right" valign="top">
                                                                Restaurant
                                                            </td>
                                                        </tr>
                                                        @foreach($order->menuItemOrders as $item)
                                                            <tr style="font-family:  'Lato', sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                                                <td style="font-family:  'Lato', sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; border-top-width: 1px; border-top-color: #eee; border-top-style: solid; margin: 0; padding: 5px 0;"
                                                                    valign="top">
                                                                    {{ $item->item->name }}
                                                                </td>
                                                                <td class="alignright"
                                                                    style="font-family:  'Lato', sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; text-align: right; border-top-width: 1px; border-top-color: #eee; border-top-style: solid; margin: 0; padding: 5px 0;"
                                                                    align="right" valign="top">
                                                                    {{ $item->item->restaurant->name }}
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                        <tr class="total"
                                                            style="font-family:  'Lato', sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                                            <td class="alignright" width="80%"
                                                                style="font-family:  'Lato', sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; text-align: right; border-top-width: 2px; border-top-color: #333; border-top-style: solid; border-bottom-color: #333; border-bottom-width: 2px; border-bottom-style: solid; font-weight: 700; margin: 0; padding: 5px 0;"
                                                                align="right" valign="top">Total
                                                            </td>
                                                            <td class="alignright"
                                                                style="font-family:  'Lato', sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; text-align: right; border-top-width: 2px; border-top-color: #333; border-top-style: solid; border-bottom-color: #333; border-bottom-width: 2px; border-bottom-style: solid; font-weight: 700; margin: 0; padding: 5px 0;"
                                                                align="right"
                                                                valign="top">{{ $order->orderPriceInfo->total_price }}
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr style="font-family:  'Lato', sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                    <td align="center">
                                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                            <tr>
                                                <td align="center" style="padding-top: 25px;" class="padding">
                                                    <table border="0" cellspacing="0" cellpadding="0"
                                                           class="mobile-button-container">
                                                        <tr>
                                                            <td align="center" style="border-radius: 3px;"
                                                                bgcolor="#256F9C"><a href="https://litmus.com"
                                                                                     target="_blank"
                                                                                     style="font-size: 16px; font-family:  'Lato', sans-serif; color: #ffffff; text-decoration: none; color: #ffffff; text-decoration: none; border-radius: 3px; padding: 15px 25px; border: 1px solid #256F9C; display: inline-block;"
                                                                                     class="mobile-button">Service
                                                                    Order &rarr;</a></td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr style="font-family:  'Lato', sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                    <td class="content-block aligncenter"
                                        style="font-family:  'Lato', sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; text-align: center; margin: 0; padding: 0 0 20px;"
                                        align="center" valign="top">
                                        <br>
                                        <strong>SewaneeEats</strong>, <br>
                                        Sewanee, TN
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
                <div class="footer"
                     style="font-family:  'Lato', sans-serif; box-sizing: border-box; font-size: 14px; width: 100%; clear: both; color: #999; margin: 0; padding: 20px;">
                    <table width="100%"
                           style="font-family:  'Lato', sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                        <tr style="font-family:  'Lato', sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                            <td class="aligncenter content-block"
                                style="font-family:  'Lato', sans-serif; box-sizing: border-box; font-size: 12px; vertical-align: top; color: #999; text-align: center; margin: 0; padding: 0 0 20px;"
                                align="center" valign="top">Questions? Email <a href="{{route('support')}}"
                                                                                style="font-family:  'Lato', sans-serif; box-sizing: border-box; font-size: 12px; color: #999; text-decoration: underline; margin: 0;">
                                    SewaneeEats
                                </a>
                        </tr>
                    </table>
                </div>
            </div>
        </td>
        <td style="font-family:  'Lato', sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0;"
            valign="top"></td>
    </tr>
</table>
</body>
</html>