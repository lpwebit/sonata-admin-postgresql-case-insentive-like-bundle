<?php

namespace Lpweb\SonataAdminPostgreSQLCaseInsensitiveLikeBundle\Filter;


use Sonata\AdminBundle\Filter\Model\FilterData;
use Sonata\AdminBundle\Form\Type\Filter\ChoiceType;
use Sonata\AdminBundle\Form\Type\Operator\StringOperatorType;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Sonata\AdminBundle\Search\SearchableFilterInterface;
use Sonata\DoctrineORMAdminBundle\Filter\Filter;
use Sonata\DoctrineORMAdminBundle\Filter\StringFilter;

class CaseInsensitiveStringFilter extends Filter implements SearchableFilterInterface {

    public const TRIM_NONE = 0;
    public const TRIM_LEFT = 1;
    public const TRIM_RIGHT = 2;
    public const TRIM_BOTH = self::TRIM_LEFT | self::TRIM_RIGHT;

    /**
     * {@inheritdoc}
     */
    public function filter(\Sonata\DoctrineORMAdminBundle\Datagrid\ProxyQueryInterface $query, string $alias, string $field, FilterData $data): void {
        $data->changeValue(trim($data->getValue()));

        if (strlen($data->getValue()) == 0) {
            return;
        }

        $type = $data->getType() ?? StringOperatorType::TYPE_CONTAINS;

        $operator = $this->getOperator($type);

        if (!$operator) {
            $operator = 'LIKE';
        }

        $parameterName = $this->getNewParameterName($query);

        $this->applyWhere($query, sprintf(
            'LOWER(%s.%s) %s :%s',
            $alias,
            $field,
            $operator,
            $parameterName
        ));

        if ($type == StringOperatorType::TYPE_EQUAL) {
            $query->setParameter($parameterName, $this->handleParameter($data->getValue()));
        } else {
            $format = $this->getOption('format') ?? "%%%s%%";
            $query->setParameter($parameterName, sprintf(
                $format,
                $this->handleParameter($data->getValue())
            ));
        }
    }

    private function getOperator($type): bool|string {
        $choices = [
            StringOperatorType::TYPE_CONTAINS     => 'LIKE',
            StringOperatorType::TYPE_NOT_CONTAINS => 'NOT LIKE',
            StringOperatorType::TYPE_EQUAL        => '=',
        ];

        return $choices[$type] ?? false;
    }

    private function handleParameter($parameter): string {
        $parameter = htmlentities($parameter, ENT_NOQUOTES, 'utf-8');
        $parameter = preg_replace(
            '#&([A-za-z])(?:acute|cedil|caron|circ|grave|orn|ring|slash|th|tilde|uml);#',
            '\1',
            $parameter
        );
        $parameter = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $parameter);
        $parameter = preg_replace('#&[^;]+;#', '', $parameter);

        return mb_strtolower($parameter);
    }

    public function isSearchEnabled(): bool {
        return $this->getOption('global_search');
    }

    public function getDefaultOptions(): array {
        return [
            'force_case_insensitivity' => false,
            'trim'                     => self::TRIM_BOTH,
            'allow_empty'              => false,
            'global_search'            => true,
        ];
    }

    public function getRenderSettings(): array {
        return [ChoiceType::class, [
            'field_type'    => $this->getFieldType(),
            'field_options' => $this->getFieldOptions(),
            'label'         => $this->getLabel(),
        ]];
    }

}