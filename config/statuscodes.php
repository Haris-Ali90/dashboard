<?php


/**
 * Status cords config
 * @Creator Adnan nadeem
 * @date   23/10/2020
 */

return [
    'competed'=>
        [
            "JCO_ORDER_DELIVERY_SUCCESS"=>17,
            "JCO_HAND_DELIEVERY" => 113,
            "JCO_DOOR_DELIVERY" => 114,
            "JCO_NEIGHBOUR_DELIVERY" => 116,
            "JCO_CONCIERGE_DELIVERY" => 117,
            "JCO_BACK_DOOR_DELIVERY" => 118,
            "JCO_OFFICE_CLOSED_DELIVERY" => 132,
            "JCO_DELIVER_GERRAGE" => 138,
            "JCO_DELIVER_FRONT_PORCH" => 139,
            "JCO_DEILVER_MAILROOM" => 144
        ],
    'return'=>
        [
            //"Joey on the way to pickup" => 101,
            "Joey Incident" => 102,
            "Delay at pickup" => 103,
            "JCO_ITEM_DAMAGED_INCOMPLETE" => 104,
            "JCO_ITEM_DAMAGED_RETURN" => 105,
            "JCO_CUSTOMER_UNAVAILABLE_DELIEVERY_RETURNED" => 106,
            "JCO_CUSTOMER_UNAVAILABLE_LEFT_VOICE" => 107,
            "JCO_CUSTOMER_UNAVAILABLE_ADDRESS" => 108,
            "JCO_CUSTOMER_UNAVAILABLE_PHONE" => 109,
            "JCO_HUB_DELIEVER_REDELIEVERY" => 110,
            "JCO_HUB_DELIEVER_RETURN" => 111,
            "JCO_ORDER_REDELIVER" => 112,
            "JCO_ORDER_RETURN_TO_HUB" => 131,
            "JCO_CUSTOMER_REFUSED_DELIVERY" => 135,
            "CLIENT_REQUEST_CANCEL_ORDER" => 136,
            "DAMAGED_ROAD" => 143
        ],

    'unattempted'=>
        [
            "JCO_ORDER_NEW" => 13,
            "JCO_ORDER_SCHEDULED" => 61,
            "JCO_ORDER_AT_HUB_PROCESSING" => 124
        ],
    'sort' =>
        [
            "JCO_PACKAGES_SORT"  =>133
        ],
    'pickup'=>
        [
           "JCO_HUB_PICKUP"=>121
        ],
        'delay'=>
        [
            "MARK_DELAY"=>255
        ]
];
