<?php

namespace Shopsys\ShopBundle\Model\Country;

use Shopsys\ShopBundle\Component\Domain\SelectedDomain;
use Shopsys\ShopBundle\Component\Grid\InlineEdit\AbstractGridInlineEdit;
use Shopsys\ShopBundle\Form\Admin\Country\CountryFormType;
use Shopsys\ShopBundle\Model\Country\CountryData;
use Shopsys\ShopBundle\Model\Country\CountryFacade;
use Shopsys\ShopBundle\Model\Country\CountryGridFactory;
use Symfony\Component\Form\FormFactory;

class CountryInlineEdit extends AbstractGridInlineEdit
{
    /**
     * @var \Shopsys\ShopBundle\Model\Country\CountryFacade
     */
    private $countryFacade;

    /**
     * @var \Shopsys\ShopBundle\Component\Domain\SelectedDomain
     */
    private $selectedDomain;

    /**
     * @var \Symfony\Component\Form\FormFactory
     */
    private $formFactory;

    public function __construct(
        CountryGridFactory $countryGridFactory,
        CountryFacade $countryFacade,
        SelectedDomain $selectedDomain,
        FormFactory $formFactory
    ) {
        parent::__construct($countryGridFactory);
        $this->countryFacade = $countryFacade;
        $this->selectedDomain = $selectedDomain;
        $this->formFactory = $formFactory;
    }

    /**
     * @param \Shopsys\ShopBundle\Model\Country\CountryData $countryData
     * @return int
     */
    protected function createEntityAndGetId($countryData)
    {
        $country = $this->countryFacade->create($countryData, $this->selectedDomain->getId());

        return $country->getId();
    }

    /**
     * @param int $countryId
     * @param \Shopsys\ShopBundle\Model\Country\CountryData $countryData
     */
    protected function editEntity($countryId, $countryData)
    {
        $this->countryFacade->edit($countryId, $countryData);
    }

    /**
     * @param int|null $countryId
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getForm($countryId)
    {
        $countryData = new CountryData();

        if ($countryId !== null) {
            $country = $this->countryFacade->getById((int)$countryId);
            $countryData->setFromEntity($country);
        }

        return $this->formFactory->create(CountryFormType::class, $countryData);
    }
}
