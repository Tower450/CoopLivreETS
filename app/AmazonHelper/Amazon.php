<?php

namespace AmazonHelper;

use ApaiIO\Configuration\GenericConfiguration;
use ApaiIO\Operations\Lookup;
use ApaiIO\ApaiIO;

class Amazon{

    public function getBookFromIsbn($isbn){

        $conf = new GenericConfiguration();

        $conf
            ->setCountry('ca')
            ->setAccessKey('AKIAIKZXUVVC2NFKQCJQ')
            ->setSecretKey('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')
            ->setAssociateTag('etcoli-20')
            ->setRequest('\ApaiIO\Request\Soap\Request')
            ->setResponseTransformer('\ApaiIO\ResponseTransformer\ObjectToArray');

        $lookup = new Lookup();
        $lookup->setIdType("ISBN");
        $lookup->setItemId($isbn);
        $lookup->setResponsegroup(array('Large','Images'));
        $apaiIo = new ApaiIO($conf);
        $response = $apaiIo->runOperation($lookup);

        if(!empty($response['Items']['Request']['Errors'])){
            return null;
        }

        $items = array();

        if(!$this->is_assoc($response['Items']['Item'])){
            foreach($response['Items']['Item'] as $item){
                $filteredItem =  $this->extractBookInfo($item);
                array_push($items,$filteredItem);
            }
        }
        else{
            $item = $response['Items']['Item'];
            $filteredItem = $this->extractBookInfo($item);
            array_push($items,$filteredItem);
        }

        return $items;
    }

    //Return the filtered item
    private function extractBookInfo($item){
        $filteredItem = array();
        $filteredItem['image_link'] = (!empty($item['MediumImage']['URL']))? $item['MediumImage']['URL']:null;
        $filteredItem['title'] = (!empty($item['ItemAttributes']['Title'])) ? $item['ItemAttributes']['Title']:null;
        $filteredItem['price'] = (!empty($item['OfferSummary']['LowestNewPrice']['Amount'])) ? $item['OfferSummary']['LowestNewPrice']['Amount']/100 : null;
        $filteredItem['ISBN_10'] = (!empty($item['ItemAttributes']['ISBN'])) ? $item['ItemAttributes']['ISBN']: null;
        $filteredItem['ISBN_13'] = (!empty($item['ItemAttributes']['EAN'])) ? $item['ItemAttributes']['EAN']: null;
        $filteredItem['author'] = (!empty($item['ItemAttributes']['Author']))? $item['ItemAttributes']['Author']:null;
        $filteredItem['pages'] =(!empty($item['ItemAttributes']['NumberOfPages'])) ?$item['ItemAttributes']['NumberOfPages']:null;

        return $filteredItem;
    }

    private function is_assoc(array $array) {
        return (bool)count(array_filter(array_keys($array), 'is_string'));
    }
}