<?php

namespace App\Model;

use App\Entity\Menu;

class MenuManager extends AbstractManager
{
    const TABLE = 'menu';

    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    public function selectMenuById(int $id): Menu
    {
        $statement = $this->pdo->prepare("SELECT * FROM $this->table WHERE id=:id");
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchObject('App\Entity\Menu');
    }

    public function insertTitleAndPrice(Menu $menu):void
    {
        $statement = $this->pdo->prepare("INSERT INTO $this->table (title , price) VALUES (:title,:price)");

        $statement->bindValue('title', $menu->getTitle(), \PDO::PARAM_STR);
        $statement->bindValue('price', $menu->getPrice(), \PDO::PARAM_INT);

        $statement->execute();
    }

    public function updateTitleAndPrice(INT $id, Menu $menu):void
    {
        $statement = $this->pdo->prepare("UPDATE $this->table SET title=:title, price=:price, status=:status WHERE id =:id");

        $statement->bindValue('title', $menu->getTitle(), \PDO::PARAM_STR);
        $statement->bindValue('price', $menu->getPrice(), \PDO::PARAM_INT);
        $statement->bindValue('status', $menu->getStatus(), \PDO::PARAM_BOOL);
        $statement->bindValue('id', $id, \PDO::PARAM_INT);

        $statement->execute();
    }
  
    public function selectAllActive()
    {
        return $this->pdo->query("SELECT * FROM $this->table WHERE status= 1")->fetchAll(\PDO::FETCH_CLASS,'App\Entity\Menu');
    }

    public function selectMenuById(int $id)
    {
        // prepared request
        $statement = $this->pdo->prepare("SELECT * FROM $this->table WHERE id=:id");
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchObject('App\Entity\Menu');
    }
    
    public function selectAllWithDishes(): array
    {
        $statement = $this->pdo->prepare("SELECT menu.title AS menu , menu.price AS price, 
                                                          dish.composition AS dish,
                                                          dish.price AS priceSolo
                                                    FROM $this->table 
                                                    JOIN menu_dish ON menu_id = menu.id
                                                    JOIN dish ON dish.id = menu_dish.dish_id 
                                                    WHERE status = 1");
        $statement->execute();

        return $statement->fetchAll();
    }
}
