<?php

namespace Hgabka\KunstmaanExtensionBundle\AdminList\FilterType\ORM;

use Kunstmaan\AdminListBundle\AdminList\FilterType\ORM\AbstractORMFilterType;
use Symfony\Component\HttpFoundation\Request;

class ChoiceFilterType extends AbstractORMFilterType
{
    /**
     * @param Request $request  The request
     * @param array   &$data    The data
     * @param string  $uniqueId The unique identifier
     */
    public function bindRequest(Request $request, array &$data, $uniqueId)
    {
        $data['comparator'] = $request->query->get('filter_comparator_'.$uniqueId);
        $data['value'] = $request->query->get('filter_value_'.$uniqueId);
    }

    /**
     * @param array  $data     The data
     * @param string $uniqueId The unique identifier
     */
    public function apply(array $data, $uniqueId)
    {
        if (isset($data['value']) && isset($data['comparator'])) {
            $colName = false === stripos($this->columnName, '.') ? $this->getAlias().$this->columnName : $this->columnName;

            switch ($data['comparator']) {
                case 'equals':
                    $this->queryBuilder->andWhere($this->queryBuilder->expr()->eq($colName, ':var_'.$uniqueId));
                    $this->queryBuilder->setParameter('var_'.$uniqueId, $data['value']);

                    break;
                case 'notequals':
                    $this->queryBuilder->andWhere($this->queryBuilder->expr()->neq($colName, ':var_'.$uniqueId));
                    $this->queryBuilder->setParameter('var_'.$uniqueId, $data['value']);

                    break;
            }
        }
    }

    /**
     * @return string
     */
    public function getTemplate()
    {
        return 'HgabkaKunstmaanExtensionBundle:FilterType:ChoiceFilter.html.twig';
    }
}
