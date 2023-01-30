<?php

require_once "model.php";

class objects_model extends model {

    function get_all() {
        $sth = $this->db->prepare("SELECT objects.id, objects.title, objects.price, objects.adress, cities.citiename, objects.mainimage
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
        $sth = $this->db->prepare("SELECT objects.id, objects.title, objects.price, objects.adress, cities.citiename, objects.mainimage
        FROM objects
        INNER JOIN cities ON objects.cityid = cities.id
        LEFT JOIN connectprop ON objects.id = connectprop.objectid
        $filterstring
        GROUP BY objects.id;");
        $sth->execute();
        return $sth->fetchAll();
    }

    function get($id) {
        $sth = $this->db->prepare("SELECT objects.*, cities.citiename
        FROM objects
        INNER JOIN cities ON objects.cityid = cities.id
        WHERE objects.id = $id;");
        $sth->execute();
        return $sth->fetch();
    }

    function delete($id) {
        $this->db->beginTransaction();
        $sth = $this->db->prepare("DELETE FROM connectprop WHERE objectid = $id;");
        try{
            $sth->execute();
        }
        catch (PDOException $e) {
            $this->db->rollback();
            return false;
        }
        $sth = $this->db->prepare("DELETE FROM inquiries WHERE objectid = $id;");
        try{
            $sth->execute();
        }
        catch (PDOException $e) {
            $this->db->rollback();
            return false;
        }
        $sth = $this->db->prepare("DELETE FROM objects WHERE id = $id;");
        try {
            $sth->execute();
        }
        catch (PDOException $e) {
            $this->db->rollback();
            return false;
        }
        $this->db->commit();
        return true;
    }

    function update($id, $data) {
        $object_data = $data["object"];
        $object_properties = $data["properties"];
        $this->db->beginTransaction();
        $sth = $this->db->prepare("DELETE FROM connectprop WHERE objectid = $id;");
        try{
            $sth->execute();
        }
        catch (PDOException $e)
        {
            $this->db->rollback();
            return false;
        }
        $sth = $this->db->prepare("UPDATE objects SET title=?,price=?,adress=?,postcode=?,cityid=?,`description`=?,mainimage=?,image2=?,image3=?,image4=?,image5=?,sold=? WHERE id=?;");
        $sold = 0;
        if($object_data["sold"] == true){
            $sold = 1;
        }
        try{
            $sth->execute([$object_data["title"],$object_data["price"],$object_data["adress"],$object_data["postcode"],$object_data["cityid"],$object_data["description"],$object_data["mainimage"],$object_data["image2"],$object_data["image3"],$object_data["image4"],$object_data["image5"],$sold, $id]);
        }
        catch (PDOException $e)
        {
            $this->db->rollback();
            return false;
        }
        foreach($object_properties as $propertie){
            $sth = $this->db->prepare("INSERT INTO connectprop(objectid,propertieid) VALUES(?,?);");
            try{
                $sth->execute([$id, $propertie]);
            }
            catch (PDOException $e)
            {
                $this->db->rollback();
                return false;
            }
        }
        $this->db->commit();
        return true;
    }

    function create($data) {
        $object_data = $data["object"];
        $object_properties = $data["properties"];
        $this->db->beginTransaction();
        $sth = $this->db->prepare("INSERT INTO objects(title,price,adress,postcode,cityid,`description`,mainimage,image2,image3,image4,image5,sold) VALUES(?,?,?,?,?,?,?,?,?,?,?,?);");
        try{
            $sth->execute([$object_data["title"],$object_data["price"],$object_data["adress"],$object_data["postcode"],$object_data["cityid"],$object_data["description"],$object_data["mainimage"],$object_data["image2"],$object_data["image3"],$object_data["image4"],$object_data["image5"],$object_data["sold"]]);
        }
        catch (PDOException $e)
        {
            $this->db->rollback();
            return false;
        }
        $last_id = $this->db->lastInsertId();
        foreach($object_properties as $propertie){
            $sth = $this->db->prepare("INSERT INTO connectprop(objectid,propertieid) VALUES(?,?);");
            try{
                $sth->execute([$last_id, $propertie]);
            }
            catch (PDOException $e)
            {
                $this->db->rollback();
                return false;
            }
        }
        $this->db->commit();
        return true;
    }

}