<?php

namespace Hgabka\KunstmaanExtensionBundle\Doctrine\Hydrator;

use Doctrine\ORM\Internal\Hydration\AbstractHydrator;
use PDO;

class KeyValueHydrator extends AbstractHydrator
{
    const HYDRATOR_NAME = 'key_value';

    /**
     * Hydrators all data at once.
     *
     * @return array
     */
    protected function hydrateAllData()
    {
        return $this->_stmt->fetchAll(PDO::FETCH_KEY_PAIR);
    }

    /**
     * Hydrates a row.
     *
     * @param array $row
     * @param array $result
     * @SuppressWarnings(PMD.ElseExpression)
     *
     * @return bool
     */
    protected function hydrateRowData(array $row, array &$result)
    {
        if (count($row) === 0) {
            return false;
        }

        $keys = array_keys($row);

        $id = $row[$keys[0]];

        if (count($row) == 2) {
            $value = $row[$keys[1]];
        } else {
            array_shift($row);
            $value = $row;
        }

        $result[$id] = $value;

        return true;
    }
}
