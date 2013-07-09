<?php

    class favorite_helper {

        public static function getUser($zapp)
        {
            $user = $zapp->request->getParam("user", NULL);

            if (empty($user))
            {
                die("invalid user provided");
            }

            return $user;
        }

        public static function connectToDB($zapp)
        {
            R::setup('mysql:host='.$zapp->config['database']['hostname'].
                    ';dbname='.$zapp->config['database']['database'],
                $zapp->config['database']['username'],
                $zapp->config['database']['password']);
        }

        public static function parseAPIGroupsResponse($response)
        {
            $result = json_decode($response);

            $elements = array();
            if (isset($result->elements))
            {
                foreach ($result->elements as $el)
                {
                    $element = new stdClass();
                    $element->name = $el->name;
                    $element->urn = $el->urn;
                    $element->urn64 = base64_encode($el->urn);
                    $elements[] = $element;
                }
            }

            return $elements;
        }
    }

