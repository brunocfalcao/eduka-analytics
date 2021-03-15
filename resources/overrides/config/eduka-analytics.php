<?php

return [

    'dns' => [

        'blacklist_servers' => [
            'dnsbl-1.uceprotect.net',
            'dnsbl-2.uceprotect.net',
            'dnsbl-3.uceprotect.net',
            'dnsbl.dronebl.org',
            'dnsbl.sorbs.net',
            'zen.spamhaus.org',
            'bl.spamcop.net',
            'list.dsbl.org',
        ],
    ],

    'goals' => [

        Eduka\Analytics\Goals\NthVisit::class,


         /*
          * 1st-time: A visitor that visited, once, the website
          * nth-times: A visitor that visited, more than once, the website.
          * purchase-click: A visitor that clicked on the purchase.
          * purchased-completed: A visitor that completed the purchase.
          * purchase-abandoned: A visitor that abandoned the purchase.
          * from-promotion: A visitor that refered from a promotional campaign.
          * from-referal: A visitor that refered from another named referal.
          * refunded: A visitor that requested a refund.
          */

    ],

    'url' => [

        // url paths that we don't want to trace at all.
        'exceptions' => [

        ],
    ],
];
