<?php

namespace BankBundle\Repository;

use Doctrine\ORM\EntityRepository;

class BankRepository extends EntityRepository
{
    public function getCount()
    {
        $total = $this->createQueryBuilder('a')
            ->select('count(a.id)')
            ->getQuery()
            ->getSingleScalarResult();
        return $total;
    }

    public function getLastTotal()
    {
        $data = $this->findOneBy(array(),array('id'=>'DESC'));

        if ($data == null) {
            return '0';
        } else {
            return $data->getTotal();
        }
    }
}
