<?php

namespace SS6\ShopBundle\Tests\Model\Image\Config;

use PHPUnit_Framework_TestCase;
use SS6\ShopBundle\Model\Image\Config\Exception\ImageEntityConfigNotFoundException;
use SS6\ShopBundle\Model\Image\Config\ImageConfigDefinition;
use SS6\ShopBundle\Model\Image\Config\ImageConfigLoader;
use SS6\ShopBundle\Model\Image\Config\ImageConfig;
use stdClass;
use Symfony\Component\Filesystem\Filesystem;

class ImageConfigTest extends PHPUnit_Framework_TestCase {

	/**
	 * @return \SS6\ShopBundle\Model\Image\Config\ImageConfig
	 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
	 */
	public function getBaseImageConfig() {
		$inputConfig = array(
			array(
				ImageConfigDefinition::CONFIG_CLASS => stdClass::class,
				ImageConfigDefinition::CONFIG_ENTITY_NAME => 'Name_1',
				ImageConfigDefinition::CONFIG_FILENAME_METHOD => 'Method_0',
				ImageConfigDefinition::CONFIG_SIZES => array(
					array(
						ImageConfigDefinition::CONFIG_SIZE_NAME => 'SizeName_0_1',
						ImageConfigDefinition::CONFIG_SIZE_WIDTH => null,
						ImageConfigDefinition::CONFIG_SIZE_HEIGHT => null,
						ImageConfigDefinition::CONFIG_SIZE_CROP => false,
					)
				),
				ImageConfigDefinition::CONFIG_TYPES => array(
					array(
						ImageConfigDefinition::CONFIG_TYPE_NAME => 'TypeName_1',
						ImageConfigDefinition::CONFIG_FILENAME_METHOD => 'Method_1',
						ImageConfigDefinition::CONFIG_SIZES => array(
							array(
								ImageConfigDefinition::CONFIG_SIZE_NAME => 'SizeName_1_1',
								ImageConfigDefinition::CONFIG_SIZE_WIDTH => null,
								ImageConfigDefinition::CONFIG_SIZE_HEIGHT => null,
								ImageConfigDefinition::CONFIG_SIZE_CROP => false,
							),
							array(
								ImageConfigDefinition::CONFIG_SIZE_NAME => null,
								ImageConfigDefinition::CONFIG_SIZE_WIDTH => 200,
								ImageConfigDefinition::CONFIG_SIZE_HEIGHT => 100,
								ImageConfigDefinition::CONFIG_SIZE_CROP => true,
							),
						),
					),
					array(
						ImageConfigDefinition::CONFIG_TYPE_NAME => 'TypeName_2',
						ImageConfigDefinition::CONFIG_FILENAME_METHOD => 'TypeName_2',
						ImageConfigDefinition::CONFIG_SIZES => array(),
					),
				),
				array(
					ImageConfigDefinition::CONFIG_CLASS => 'Class_1',
					ImageConfigDefinition::CONFIG_ENTITY_NAME => 'Name_2',
					ImageConfigDefinition::CONFIG_FILENAME_METHOD => 'Method_3',
					ImageConfigDefinition::CONFIG_SIZES => array(),
					ImageConfigDefinition::CONFIG_TYPES => array(),
				),
			),
		);

		$filesystem = new Filesystem();
		$imageConfigLoader = new ImageConfigLoader($filesystem);
		$imageEntityConfigByClass = $imageConfigLoader->loadFromArray($inputConfig);

		return new ImageConfig($imageEntityConfigByClass);
	}

	public function testGetEntityName() {
		$imageConfig = $this->getBaseImageConfig();
		$entity = new stdClass();

		$this->assertEquals('Name_1', $imageConfig->getEntityName($entity));
	}

	public function testGetEntityNameNotFound() {
		$imageConfig = $this->getBaseImageConfig();

		$this->setExpectedException(ImageEntityConfigNotFoundException::class);
		$imageConfig->getEntityName($this);
	}

	public function testGetImageSizeConfigByEntity() {
		$imageConfig = $this->getBaseImageConfig();
		$entity = new stdClass();

		$imageSizeConfig1 = $imageConfig->getImageSizeConfigByEntity($entity, 'TypeName_1', 'SizeName_1_1');
		$this->assertEquals('SizeName_1_1', $imageSizeConfig1->getName());

		$imageSizeConfig2 = $imageConfig->getImageSizeConfigByEntity($entity, 'TypeName_1', null);
		$this->assertNull($imageSizeConfig2->getName());
		$this->assertEquals(200, $imageSizeConfig2->getWidth());
		$this->assertEquals(100, $imageSizeConfig2->getHeight());
		$this->assertTrue($imageSizeConfig2->getCrop());

		$imageSizeConfig3 = $imageConfig->getImageSizeConfigByEntity($entity, null, 'SizeName_0_1');
		$this->assertEquals('SizeName_0_1', $imageSizeConfig3->getName());
	}

	public function testGetImageSizeConfigByEntityName() {
		$imageConfig = $this->getBaseImageConfig();
		$entityName = 'Name_1';

		$imageSizeConfig1 = $imageConfig->getImageSizeConfigByEntityName($entityName, 'TypeName_1', 'SizeName_1_1');
		$this->assertEquals('SizeName_1_1', $imageSizeConfig1->getName());

		$imageSizeConfig2 = $imageConfig->getImageSizeConfigByEntityName($entityName, 'TypeName_1', null);
		$this->assertNull($imageSizeConfig2->getName());
		$this->assertEquals(200, $imageSizeConfig2->getWidth());
		$this->assertEquals(100, $imageSizeConfig2->getHeight());
		$this->assertTrue($imageSizeConfig2->getCrop());

		$imageSizeConfig3 = $imageConfig->getImageSizeConfigByEntityName($entityName, null, 'SizeName_0_1');
		$this->assertEquals('SizeName_0_1', $imageSizeConfig3->getName());
	}

	public function tesGetImageEntityConfig() {
		$imageConfig = $this->getBaseImageConfig();
		$entity = new stdClass();

		$imageEntityConfig = $imageConfig->getImageEntityConfig($entity);
		$this->assertEquals('Name_1', $imageEntityConfig->getEntityName());
	}

	public function tesGetImageEntityConfigNotFound() {
		$imageConfig = $this->getBaseImageConfig();

		$this->setExpectedException(ImageEntityConfigNotFoundException::class);
		$imageConfig->getImageEntityConfig($this);
	}

}
