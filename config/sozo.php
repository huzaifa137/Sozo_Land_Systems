<?php

return [
    /*
     | When true, a receipt's posted balance must equal (buyer's current balance - amount paid).
     | When false (default) a different balance is accepted, because admins type it by hand and
     | legacy rows may hold stale balances, but every mismatch is written to the log for audit.
     */
    'strict_receipt_balance' => env('STRICT_RECEIPT_BALANCE', false),

    /*
     | New receipt numbers start above the old random 10000-50000 range so they can never
     | collide with receipts that were already issued.
     */
    'receipt_number_offset' => 50000,
];
