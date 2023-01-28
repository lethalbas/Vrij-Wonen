<?php

require_once "model.php";

class objects_model extends model {

    function get_all() {
        $sth = $this->db->prepare("SELECT objects.id, objects.title, objects.adress, cities.citiename, objects.mainimage
            FROM objects
            INNER JOIN cities ON objects.cityid = cities.id");
        $sth->execute();
        return $sth->fetchAll();
    }

    // filter by properties
    function get_all_filtered($filters) {
        $filterstring = "";
        if(count($filters) > 0){
            $filterstring = $filterstring . "WHERE";
            foreach($filters as $filter => $val) {
                // arrays
                if(is_array($val)){
                    foreach($val as $arr_item){
                        // strings in arrays
                        if(is_string($val)){
                            $newstr = " $filter = '$arr_item' AND";
                            $filterstring = $filterstring . $newstr;
                        }
                        // integers in arrays (used for id)
                        else{
                            $newstr = " $filter = $arr_item AND";
                            $filterstring = $filterstring . $newstr;
                        }
                    }
                }
                // strings
                if(is_string($val)){
                    $newstr = " $filter = '$val' AND";
                    $filterstring = $filterstring . $newstr;
                }
                // integers (used for id)
                else{
                    $newstr = " $filter = $val AND";
                    $filterstring = $filterstring . $newstr;
                }
            }
            $filterstring = rtrim($filterstring, ' AND');
        }
        $sth = $this->db->prepare("SELECT objects.id, objects.title, objects.adress, cities.citiename, objects.mainimage
        FROM objects
        INNER JOIN cities ON objects.cityid = cities.id
        LEFT JOIN connectprop ON objects.id = connectprop.objectid
        $filterstring
        GROUP BY objects.id;");
        $sth->execute();
        return $sth->fetchAll();
    }

    function get($id) {
        $sth = $this->db->prepare("SELECT * FROM objects WHERE objects.id = $id;");
        $sth->execute();
        return $sth->fetch();
    }

    function delete($id) {
        $this->db->beginTransaction();
        $sth = $this->db->prepare("DELETE FROM connectprop WHERE objectid = $id;");
        $sth->execute();
        $sth = $this->db->prepare("DELETE FROM objects WHERE id = $id;");
        $sth->execute();
        $this->db->commit();
    }

    function update($id, $data) {
        $object_data = $data["object"];
        $object_properties = $data["properties"];
        $this->db->beginTransaction();
        $sth = $this->db->prepare("DELETE FROM connectprop WHERE objectid = $id;");
        $sth->execute();
        $sth = $this->db->prepare("UPDATE objects SET title=?,price=?,adress=?,postcode=?,cityid=?,`description`=?,mainimage=?,image2=?,image3=?,image4=?,image5=?,sold=?) WHERE id=?;");
        $sth->execute([$object_data["title"],$object_data["price"],$object_data["adress"],$object_data["postcode"],$object_data["cityid"],$object_data["description"],$object_data["mainimage"],$object_data["image2"],$object_data["image3"],$object_data["image4"],$object_data["image5"],$object_data["sold"], $id]);
        foreach($object_properties as $propertie){
            $sth = $this->db->prepare("INSERT INTO connectprop(objectid,propertieid) VALUES(?,?);");
            $sth->execute([$id, $propertie]);
        }
        $this->db->commit();
    }

    function create($data) {
        $object_data = $data["object"];
        $object_properties = $data["properties"];
        $this->db->beginTransaction();
        $sth = $this->db->prepare("INSERT INTO objects(title,price,adress,postcode,cityid,`description`,mainimage,image2,image3,image4,image5,sold) VALUES(?,?,?,?,?,?,?,?,?,?,?,?);");
        $sth->execute([$object_data["title"],$object_data["price"],$object_data["adress"],$object_data["postcode"],$object_data["cityid"],$object_data["description"],$object_data["mainimage"],$object_data["image2"],$object_data["image3"],$object_data["image4"],$object_data["image5"],$object_data["sold"]]);
        $last_id = $this->db->lastInsertId();
        foreach($object_properties as $propertie){
            $sth = $this->db->prepare("INSERT INTO connectprop(objectid,propertieid) VALUES(?,?);");
            $sth->execute([$last_id, $propertie]);
        }
        $this->db->commit();
    }

}