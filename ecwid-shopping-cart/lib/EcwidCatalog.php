<?php

class EcwidCatalog
{
    var $store_id = 0;
    var $store_base_url = '';
    var $ecwid_api = null;

    function __construct($store_id, $store_base_url, $development=false)
    {
        $this->store_id = intval($store_id);
        $this->store_base_url = $store_base_url;
        $this->ecwid_api = new EcwidProductApi($this->store_id, $development);
    }

    function EcwidCatalog($store_id)
    {
        if(version_compare(PHP_VERSION,"5.0.0","<"))
            $this->__construct($store_id);
    }

    function get_product($id)
    {
        $params = array
        (
            array("alias" => "p", "action" => "product", "params" => array("id" => $id)),
            array("alias" => "pf", "action" => "profile")
        );

        $batch_result = $this->ecwid_api->get_batch_request($params);
        $product = $batch_result["p"];
        $profile = $batch_result["pf"];
        $return = '';

        if (!is_array($product)) {
            return '';
        }

        $return = "<div itemscope itemtype=\"http://schema.org/Product\">";
        $return .= "<h1 class='ecwid_catalog_product_name' itemprop=\"name\">" . htmlentities($product["name"], ENT_COMPAT, 'UTF-8') . "</h1>";
        $return .= "<p class='ecwid_catalog_product_sku' itemprop=\"sku\">" . htmlentities($product["sku"], ENT_COMPAT, 'UTF-8') . "</p>";

        if (!empty($product["thumbnailUrl"]))
        {
            $return .= "<div class='ecwid_catalog_product_image'><img itemprop=\"image\" src='" . $product["thumbnailUrl"] . "' alt='" . htmlentities($product["sku"], ENT_COMPAT, 'UTF-8') . " " . htmlentities($product["name"], ENT_COMPAT, 'UTF-8') . "'/></div>";
        }

        if(is_array($product["categories"]))
        {
            foreach ($product["categories"] as $ecwid_category)
            {
                if($ecwid_category["defaultCategory"]==true)
                {
                    $return .="<div class='ecwid_catalog_product_category'>".$ecwid_category["name"]."</div>";
                }
            }
        }

        $return .= "<div class='ecwid_catalog_product_price' itemprop=\"offers\" itemscope itemtype=\"http://schema.org/Offer\">Price: <span itemprop=\"price\">" . $product["price"] . "</span>&nbsp;<span itemprop=\"priceCurrency\">" . $profile["currency"] . "</span>";

        if (!isset($product['quantity']) || (isset($product['quantity']) && $product['quantity'] > 0))
        {
            $return .= "<div class='ecwid_catalog_quantity' itemprop=\"availability\" itemscope itemtype=\"http://schema.org/InStock\"><span>In Stock</span></div>";
        }

        $return .= "</div>";

        $return .= "<div class='ecwid_catalog_product_description' itemprop=\"description\">" . $product["description"] . "</div>";

        if (is_array($product["options"]))
        {
            foreach($product["options"] as $product_options)
            {
                $return .= "<div> $product_options[name]";
                if (isset($product_options['choices'])) {
                    $return .= ': ';
                    $choices = array();
                    foreach ($product_options['choices'] as $choice) {
                        $choices[] = $choice['text'];
                    }
                    $return .= implode(', ', $choices);
                }
                $return .= "</div>";
            }
        }


        if (is_array($product["galleryImages"]))
        {
            foreach ($product["galleryImages"] as $galleryimage)
            {
                if (empty($galleryimage["alt"]))  $galleryimage["alt"] = htmlspecialchars($product["name"]);
                $return .= "<img src='" . $galleryimage["url"] . "' alt='" . htmlspecialchars($galleryimage["alt"]) ."' title='" . htmlspecialchars($galleryimage["alt"]) ."'><br />";
            }
        }
        if (is_array($product['attributes'])) {
            foreach ($product['attributes'] as $attribute) {
                $value = $attribute['value'];

                if ($attribute['internalName'] == 'Brand') {
                    $value = "<span itemprop=\"brand\">$value</span>";
                }
                $return .= "<div>$attribute[name]: $value</div>";
            }
        }

        $return .= "</div>" . PHP_EOL;

        return $return;
    }

    function get_category($id)
    {
        $params = array
        (
            array("alias" => "category", "action" => "category", "params" => array("id" => $id)),
            array("alias" => "c", "action" => "categories", "params" => array("parent" => $id)),
            array("alias" => "p", "action" => "products", "params" => array("category" => $id)),
            array("alias" => "pf", "action" => "profile")
        );

        $batch_result = $this->ecwid_api->get_batch_request($params);

        $categories = $batch_result["c"];
        $products   = $batch_result["p"];
        $profile    = $batch_result["pf"];
        $category   = $batch_result["category"];

        $return = '<div class="ecwid_catalog_category_name">' . $category["name"] . '</div>';
        $return .= '<div class="ecwid_catalog_category_description">' . $category["description"] . '</div>';

        if (is_array($categories)) 
        {
            foreach ($categories as $category) 
            {
                $category_url = $this->build_url($category["url"]);
                $category_name = $category["name"];
                $return .= "<div class='ecwid_catalog_category_name'><a href='" . htmlspecialchars($category_url) . "&offset=0&sort=nameAsc'>" . $category_name . "</a><br /></div>" . PHP_EOL;
            }
        }

        if (is_array($products)) 
        {
            foreach ($products as $product) 
            {
                $product_url = $this->store_base_url . "#!/~/product/category=" . $id . "&id=" . $product["id"];
                $this->build_url($product["url"]);
                $product_name = $product["name"];
                $product_price = $product["price"] . "&nbsp;" . $profile["currency"];
                $return .= "<div>";
                $return .= "<span class='ecwid_product_name'><a href='" . htmlspecialchars($product_url) . "'>" . $product_name . "</a></span>";
                $return .= "&nbsp;&nbsp;<span class='ecwid_product_price'>" . $product_price . "</span>";
                $return .= "</div>" . PHP_EOL;
            }
        }

        return $return;
    }

    function build_url($url_from_ecwid)
    {
        if (preg_match('/(.*)(#!)(.*)/', $url_from_ecwid, $matches))
            return $this->store_base_url . $matches[2] . $matches[3]; 
        else
            return '';
    }
}
