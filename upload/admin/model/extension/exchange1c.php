<?php
/**
 * Created by PhpStorm.
 * User: uSkyQ
 * Date: 22.03.2017
 */

/**
 * Создает таблицы, нужные для работы
 */
class ModelExtensionExchange1c extends Model
{
    private $CATEGORIES = [];
    private $rootId = 0;
    private $rootUID = 0;
    private $PROPERTIES = [];
    private $PRODUCT_IDS = null;
    private $update_product_fields = [];
    private $update_product_desc_fields = [];
    private $COUNT_EVENTS = 2;
    private $enable_log = false;
    private $arrayProductTables = [
        'product',
        'product_attribute',
        'product_description',
        'product_discount',
        'product_image',
        'product_option',
        'product_option_value',
        'product_related',
        'product_reward',
        'product_special',
        'product_to_1c',
        'product_to_category',
        'product_to_download',
        'product_to_layout',
        'product_to_store',
        'offers_product',
    ];
    private $arrayRelatedOptionsTables = [
        'relatedoptions_to_char',
        'relatedoptions',
        'relatedoptions_option',
        'relatedoptions_variant',
        'relatedoptions_variant_option',
        'relatedoptions_variant_product',
    ];
    private $arrayOptionTables = [
        'option_value_description',
        'option_description',
        'option_value',
        'option',
        'order_option',
    ];
    private $arrayCategoryTables = [
        'category',
        'category_description',
        'category_to_store',
        'category_to_layout',
        'category_path',
        'category_to_1c',
        'category_to_layout',
    ];
    private $arrayManufacturerTables = [
        'manufacturer',
        'manufacturer_description',
        'manufacturer_to_store',
    ];
    private $arrayAttributeTables = [
        'attribute',
        'attribute_description',
        'attribute_to_1c',
        'attribute_group',
        'attribute_group_description',
    ];

    public function install()
    {
        $this->enable_log = true;
        $this->writeLog('Install module exchange1c start...');

        $this->load->model('extension/event');
        $this->model_extension_event->addEvent('ex1c_1', 'admin/model/catalog/product/deleteProduct/after',     'extension/module/exchange1c/deleteProductAfter');
        $this->model_extension_event->addEvent('ex1c_2', 'admin/model/catalog/category/deleteCategory/after',   'extension/module/exchange1c/deleteCategoryAfter');

        for ($i = 1; $i <= $this->COUNT_EVENTS; $i++) {
            $this->writeLog("— addEvent('exchange1c ex1c_" . $i . "')");
        }
        $this->writeLog('END');
    }

    /**
     *
     */
    public function uninstall()
    {
        $this->enable_log = true;
        $this->writeLog('Uninstall module exchange1c start...');

        $this->load->model('extension/event');
        for ($i = 1; $i <= $this->COUNT_EVENTS; $i++) {
            $this->model_extension_event->deleteEvent('ex1c_' . $i);
            $this->writeLog("— deleteEvent('exchange1c ex1c_" . $i . "')");
        }

        $this->writeLog('END');
    }
    public function checkDbSheme()
    {
        $query = $this->db->query('SHOW TABLES LIKE "' . DB_PREFIX . 'product_to_1c"');

        if (!$query->num_rows) {
            $this->db->query(
                'CREATE TABLE
						`' . DB_PREFIX . 'product_to_1c` (
							`product_id` int(11) NOT NULL,
							`1c_id` varchar(255) NOT NULL,
							KEY (`product_id`),
							KEY `1c_id` (`1c_id`),
							FOREIGN KEY (product_id) REFERENCES ' . DB_PREFIX . 'product(product_id) ON DELETE CASCADE
						) ENGINE=MyISAM DEFAULT CHARSET=utf8'
            );
        }

        $query = $this->db->query('SHOW TABLES LIKE "' . DB_PREFIX . 'category_to_1c"');

        if (!$query->num_rows) {
            $this->db->query(
                'CREATE TABLE
						`' . DB_PREFIX . 'category_to_1c` (
							`category_id` int(11) NOT NULL,
							`1c_category_id` varchar(255) NOT NULL,
							KEY (`category_id`),
							KEY `1c_id` (`1c_category_id`),
							FOREIGN KEY (category_id) REFERENCES ' . DB_PREFIX . 'category(category_id) ON DELETE CASCADE
						) ENGINE=MyISAM DEFAULT CHARSET=utf8'
            );
        }

        $query = $this->db->query('SHOW TABLES LIKE "' . DB_PREFIX . 'attribute_to_1c"');

        if (!$query->num_rows) {
            $this->db->query(
                'CREATE TABLE
						`' . DB_PREFIX . 'attribute_to_1c` (
							`attribute_id` int(11) NOT NULL,
							`1c_attribute_id` varchar(255) NOT NULL,
							KEY (`attribute_id`),
							KEY `1c_id` (`1c_attribute_id`),
							FOREIGN KEY (attribute_id) REFERENCES ' . DB_PREFIX . 'attribute(attribute_id) ON DELETE CASCADE
						) ENGINE=MyISAM DEFAULT CHARSET=utf8'
            );
        }
    }

    public function flushDb($params)
    {
        $this->enable_log = $this->config->get('exchange1c_full_log');
        // Удаляем товары
        if ($params['product']) {
            $this->writeLog("Очистка таблиц товаров: ");
            $this->cleanTable($this->arrayProductTables);
            if ($this->config->get('exchange1c_relatedoptions')) {
                $this->writeLog("Очистка таблиц связанных опций: ");
                $this->cleanTable($this->arrayRelatedOptionsTables);
            }
            $this->writeLog("Очистка таблиц опций: ");
            $this->cleanTable($this->arrayOptionTables);

            $this->cleanUrlAlias('product_id');
        }
        // Очищает таблицы категорий
        if ($params['category']) {
            $this->writeLog("Очистка таблиц категорий:");
            $this->cleanTable($this->arrayCategoryTables);
            $this->cleanUrlAlias('category_id');
        }
        // Очищает таблицы от всех производителей
        if ($params['manufacturer']) {
            $this->writeLog("Очистка таблиц производителей:");
            $this->cleanTable($this->arrayManufacturerTables);
            $this->cleanUrlAlias('manufacturer_id');
        }
        // Очищает атрибуты
        if ($params['attribute']) {
            $this->writeLog("Очистка таблиц атрибутов:");
            $this->cleanTable($this->arrayAttributeTables);
        }
        // Выставляем кол-во товаров в 0
        if ($params['quantity']) {
            $this->db->query('UPDATE ' . DB_PREFIX . 'product ' . 'SET quantity = 0');
        }

    }

    private function writeLog($text)
    {
        if ($this->enable_log)
            $this->log->write($text);
    }

    private function cleanTable($arrayTable)
    {
        foreach ($arrayTable as $table) {
            $query = $this->db->query("SHOW TABLES FROM " . DB_DATABASE . " LIKE '" . DB_PREFIX . $table . "'");
            if ($query->num_rows) {
                $queryText = 'TRUNCATE TABLE `' . DB_PREFIX . $table . '`';
                $this->db->query($queryText);
                $this->writeLog($queryText);
            }
        }
    }

    private function cleanUrlAlias($parameter)
    {
        $queryText = 'DELETE FROM ' . DB_PREFIX . 'url_alias WHERE query LIKE "%' . $parameter . '=%"';
        $this->db->query($queryText);
        $this->writeLog($queryText);
    }

    public function parseImport($filename, $language_id)
    {

        $importFile = DIR_CACHE . 'exchange1c/' . $filename;

        $this->enable_log = $this->config->get('exchange1c_full_log');

        $apply_watermark = $this->config->get('exchange1c_apply_watermark');
        $root_category_is_catalog = $this->config->get('exchange1c_root_category_is_catalog');

        $xml = simplexml_load_file($importFile);
        $data = [];

        if ($root_category_is_catalog) {
            if ($xml->Каталог) $this->insertRootCategory($xml->Каталог, 0, $language_id);
        } else {
            // Группы
            if ($xml->Классификатор->Группы) $this->insertCategory($xml->Классификатор->Группы->Группа, $this->rootId, $language_id);
        }
        // Свойства
        if ($xml->Классификатор->Свойства) $this->insertAttribute($xml->Классификатор->Свойства->Свойство, $language_id);


        $this->load->model('catalog/manufacturer');

        // Товары
        if ($xml->Каталог->Товары->Товар) {
            foreach ($xml->Каталог->Товары->Товар as $product) {

                $uuid = explode('#', (string)$product->Ид);
                $data['1c_id'] = $uuid[0];

                $data['model'] = $product->Артикул ? (string)$product->Артикул : 'не задана';
                $data['name'] = $product->Наименование ? (string)$product->Наименование : 'не задано';
                $data['weight'] = $product->Вес ? (float)$product->Вес : null;
                $data['sku'] = $product->Артикул ? (string)$product->Артикул : '';

                $this->writeLog("Найден товар: " . $data['name'] . "\tарт: " . $data['sku'] . "\t1C UUID: " . $data['1c_id']);

                if ($product->Картинка) {
                    $data['image'] = $apply_watermark ? $this->applyWatermark((string)$product->Картинка[0]) : (string)$product->Картинка[0];
                    unset($product->Картинка[0]);
                    foreach ($product->Картинка as $image) {
                        $data['product_image'][] = [
                            'image'      => $apply_watermark ? $this->applyWatermark((string)$image) : (string)$image,
                            'sort_order' => 0,
                        ];
                    }
                }
                /*Auth: VarIzo Task: {} Date:24.03.2017 !Start!*/
                /*Comment: Я хз зачем кусок кода ниже так как phpstorm показывает что переменные нигде не используются*/
                if ($product->ХарактеристикиТовара) {
                    $count_options = count($product->ХарактеристикиТовара->ХарактеристикаТовара);
                    $option_desc = '';
                    foreach ($product->ХарактеристикиТовара->ХарактеристикаТовара as $option) {
                        $option_desc .= (string)$option->Наименование . ': ' . (string)$option->Значение . ';';
                    }
                    $option_desc .= ";\n";
                }
                /*!End!*/
                if ($root_category_is_catalog) {
                    $data['category_1c_id'] = $this->rootUID;
                } else {
                    if ($product->Группы) $data['category_1c_id'] = $product->Группы->Ид;
                }
                if ($product->Описание) $data['description'] = (string)$product->Описание;
                if ($product->Статус) $data['status'] = (string)$product->Статус;

                // Свойства продукта
                if ($product->ЗначенияСвойств) {
                    $this->writeLog("   загружаются свойства... ");
                    foreach ($product->ЗначенияСвойств->ЗначенияСвойства as $property) {

                        if (isset($this->PROPERTIES[(string)$property->Ид]['name'])) {

                            $attribute = $this->PROPERTIES[(string)$property->Ид];

                            if (isset($attribute['values'][(string)$property->Значение])) {
                                $attribute_value = str_replace("'", "&apos;", (string)$attribute['values'][(string)$property->Значение]);
                            } else if ((string)$property->Значение != '') {
                                $attribute_value = str_replace("'", "&apos;", (string)$property->Значение);
                            } else {
                                continue;
                            }

                            $this->writeLog("   > " . $attribute_value);
                            switch ($attribute['name']) {
                                case 'Производитель':
                                    $manufacturer_name = $attribute_value;
                                    $query = $this->db->query("SELECT manufacturer_id FROM " . DB_PREFIX . "manufacturer WHERE name='" . $manufacturer_name . "'");

                                    if ($query->num_rows) {
                                        $data['manufacturer_id'] = $query->row['manufacturer_id'];
                                    } else {
                                        $data_manufacturer = [
                                            'name'               => $manufacturer_name,
                                            'keyword'            => '',
                                            'sort_order'         => 0,
                                            'manufacturer_store' => [0 => 0],
                                        ];

                                        $data_manufacturer['manufacturer_description'] = [
                                            $language_id => [
                                                'meta_keyword'     => '',
                                                'meta_description' => '',
                                                'description'      => '',
                                                'seo_title'        => '',
                                                'seo_h1'           => '',
                                            ],
                                        ];

                                        $manufacturer_id = $this->model_catalog_manufacturer->addManufacturer($data_manufacturer);
                                        $data['manufacturer_id'] = $manufacturer_id;

                                        if ($this->config->get('exchange1c_seo_url')) {
                                            $man_name = "brand-" . $manufacturer_name;
                                            $this->setSeoURL('manufacturer_id', $manufacturer_id, $man_name);
                                        }
                                    }
                                    break;

                                case 'oc.seo_h1':
                                    $data['seo_h1'] = $attribute_value;
                                    break;

                                case 'oc.seo_title':
                                    $data['seo_title'] = $attribute_value;
                                    break;

                                case 'oc.sort_order':
                                    $data['sort_order'] = $attribute_value;
                                    break;

                                default:
                                    $data['product_attribute'][] = [
                                        'attribute_id'                  => $attribute['id'],
                                        'product_attribute_description' => [
                                            $language_id => [
                                                'text' => $attribute_value,
                                            ],
                                        ],
                                    ];


                            }
                        }
                    }

                    $this->writeLog("   свойства загружены... ");
                }

                // Реквизиты продукта
                if ($product->ЗначенияРеквизитов) {
                    foreach ($product->ЗначенияРеквизитов->ЗначениеРеквизита as $requisite) {
                        switch ($requisite->Наименование) {
                            case 'Вес':
                                $data['weight'] = $requisite->Значение ? (float)$requisite->Значение : 0;
                                break;

                            case 'ОписаниеВФорматеHTML':
                                $data['description'] = $requisite->Значение ? (string)$requisite->Значение : '';
                                break;
                        }
                    }
                }

                $this->setProduct($data, $language_id);
                unset($data);
            }
        }
        unset($xml);
        $this->writeLog("Окончен разбор файла: " . $filename);
    }

    /**
     * Функция добавляет корневую категорию и всех детей
     *
     * @param    SimpleXMLElement
     * @param    int
     */

    private function insertRootCategory($xml, $parent = 0, $language_id)
    {

        $this->load->model('catalog/category');
        $req = [];
        foreach ($xml as $category) {
            if (isset($category->Ид) && isset($category->Наименование)) {
                $id = (string)$category->Ид;
                $this->log->write('category->Ид'.$id);
                $query = $this->db->query('SELECT * FROM `' . DB_PREFIX . 'category_to_1c` WHERE `1c_category_id` = "' . $this->db->escape($id) . '"');

                if ($query->num_rows) {
                    $category_id = (int)$query->row['category_id'];
                    $data = $this->model_catalog_category->getCategory($category_id);
                    $data['category_description'] = $this->model_catalog_category->getCategoryDescriptions($category_id);
                    $parent = $data['parent_id'];
                    $data = $this->initCategory($category, $parent, $data, $language_id);
                    $this->model_catalog_category->editCategory($category_id, $data);
                } else {
                    $data = $this->initCategory($category, $parent, [], $language_id);
                    $category_id = $this->model_catalog_category->addCategory($data);
                    $this->db->query('INSERT INTO `' . DB_PREFIX . 'category_to_1c` SET category_id = ' . (int)$category_id . ', `1c_category_id` = "' . $this->db->escape($id) . '"');
                }

                $this->rootId = $category_id;
                $this->rootUID = $id;
            }
            //только если тип 'translit'
            if ($this->config->get('exchange1c_seo_url')) {
                $cat_name = "cat-" . $data['parent_id'] . "-" . $data['category_description'][$language_id]['name'];
                $this->setSeoURL('category_id', $category_id, $cat_name);
            }
        }

        unset($xml);
        return $req;
    }

    /**
     * Инициализируем данные для категории дабы обновлять данные, а не затирать
     *
     * @param    array    старые данные
     * @param    int      id родительской категории
     * @param    array    новые данные
     *
     * @return    array
     */
    private function initCategory($category, $parent, $data = [], $language_id)
    {

        $result = [
            'status'         => isset($data['status']) ? $data['status'] : 1,
            'top'            => isset($data['top']) ? $data['top'] : 1,
            'parent_id'      => $parent,
            'category_store' => isset($data['category_store']) ? $data['category_store'] : [0],
            'keyword'        => isset($data['keyword']) ? $data['keyword'] : '',
            'image'          => (isset($category->Картинка)) ? (string)$category->Картинка : ((isset($data['image'])) ? $data['image'] : ''),
            'sort_order'     => (isset($category->Сортировка)) ? (int)$category->Сортировка : ((isset($data['sort_order'])) ? $data['sort_order'] : 0),
            'column'         => 1,
        ];

        $result['category_description'] = [
            $language_id => [
                'name'             => (string)$category->Наименование,
                'meta_keyword'     => (isset($data['category_description'][$language_id]['meta_keyword'])) ? $data['category_description'][$language_id]['meta_keyword'] : '',
                'meta_description' => (isset($data['category_description'][$language_id]['meta_description'])) ? $data['category_description'][$language_id]['meta_description'] : '',
                'description'      => (isset($category->Описание)) ? (string)$category->Описание : ((isset($data['category_description'][$language_id]['description'])) ? $data['category_description'][$language_id]['description'] : ''),
//                'seo_title'         => (isset($data['category_description'][$language_id]['seo_title'])) ? $data['category_description'][$language_id]['seo_title'] : '',
//                'seo_h1'            => (isset($data['category_description'][$language_id]['seo_h1'])) ? $data['category_description'][$language_id]['seo_h1'] : '',
                'meta_title'       => '',
                'meta_h1'          => '',
            ],
        ];

        return $result;
    }

    private function setSeoURL($url_type, $element_id, $element_name)
    {
        $this->writeLog('setSeoURL for url_type: ' . $url_type);
        $this->writeLog('————————————— id: ' . $element_id);
        $this->writeLog('————————————— name: ' . $element_name);
        $text = "DELETE FROM `" . DB_PREFIX . "url_alias` WHERE `query` = '" . $url_type . "=" . $element_id . "'";
        $this->writeLog('SQL : ' . $text);
        $this->db->query($text);
        $text = "INSERT INTO `" . DB_PREFIX . "url_alias` SET `query` = '" . $url_type . "=" . $element_id . "', `keyword`='" . $this->transString($element_name) . "'";
        $this->writeLog('SQL : ' . $text);
        $this->db->query($text);
    }
    /**
     * Транслиетрирует RUS->ENG
     * @param string $aString
     * @return string type
     */
    private function transString($aString) {
        $rus = array(" ", "/", "*", "-", "+", "`", "!", "@", "#", "$", "%", "^", "&", "*", "(", ")", "_", "+", "[", "]", "{", "}", "~", ";", ":", "'", "\"", "<", ">", ",", ".", "?", "А", "Б", "В", "Г", "Д", "Е", "З", "И", "Й", "К", "Л", "М", "Н", "О", "П", "Р", "С", "Т", "У", "Ф", "Х", "Ъ", "Ы", "Ь", "Э", "а", "б", "в", "г", "д", "е", "з", "и", "й", "к", "л", "м", "н", "о", "п", "р", "с", "т", "у", "ф", "х", "ъ", "ы", "ь", "э", "ё",  "ж",  "ц",  "ч",  "ш",  "щ",   "ю",  "я",  "Ё",  "Ж",  "Ц",  "Ч",  "Ш",  "Щ",   "Ю",  "Я");
        $lat = array("-", "-", "-", "-", "-", "-", "-", "-", "-", "-", "-", "-", "-", "-", "-", "-", "-", "-", "-", "-", "-", "-", "-", "-", "-", "-",  "-", "-", "-", "-", "-", "-", "a", "b", "v", "g", "d", "e", "z", "i", "y", "k", "l", "m", "n", "o", "p", "r", "s", "t", "u", "f", "h", "",  "i", "",  "e", "a", "b", "v", "g", "d", "e", "z", "i", "j", "k", "l", "m", "n", "o", "p", "r", "s", "t", "u", "f", "h", "",  "i", "",  "e", "yo", "zh", "ts", "ch", "sh", "sch", "yu", "ya", "yo", "zh", "ts", "ch", "sh", "sch", "yu", "ya");

        $string = str_replace($rus, $lat, $aString);

        while (mb_strpos($string, '--')) {
            $string = str_replace('--', '-', $string);
        }

        $string = strtolower(trim($string, '-'));

        return $string;
    }
    /**
     * Функция добавляет корневую категорию и всех детей
     *
     * @param    SimpleXMLElement
     * @param    int
     */

    private function insertCategory($xml, $parent = 0, $language_id)
    {

        $this->load->model('catalog/category');

        foreach ($xml as $category) {

            if (isset($category->Ид) && isset($category->Наименование)) {
                $id = (string)$category->Ид;
                $query = $this->db->query('SELECT * FROM `' . DB_PREFIX . 'category_to_1c` WHERE `1c_category_id` = "' . $this->db->escape($id) . '"');

                if ($query->num_rows) {
                    $category_id = (int)$query->row['category_id'];
                    $data = $this->model_catalog_category->getCategory($category_id);
                    $data['category_description'] = $this->model_catalog_category->getCategoryDescriptions($category_id);
                    $data = $this->initCategory($category, $parent, $data, $language_id);
                    $this->model_catalog_category->editCategory($category_id, $data);
                } else {
                    $data = $this->initCategory($category, $parent, [], $language_id);
                    $category_id = $this->model_catalog_category->addCategory($data);
                    $this->db->query('INSERT INTO `' . DB_PREFIX . 'category_to_1c` SET category_id = ' . (int)$category_id . ', `1c_category_id` = "' . $this->db->escape($id) . '"');
                }

                $this->CATEGORIES[$id] = $category_id;
            }

            if ($this->config->get('exchange1c_seo_url')) {
                $cat_name = "category-" . $data['parent_id'] . "-" . $data['category_description'][$language_id]['name'];
                $this->setSeoURL('category_id', $category_id, $cat_name);
            }

            if ($category->Группы) $this->insertCategory($category->Группы->Группа, $category_id, $language_id);
        }

        unset($xml);
    }

    /**
     * Создает атрибуты из свойств
     *
     * @param    SimpleXMLElement
     */
    private function insertAttribute($xml, $language_id)
    {
        $this->load->model('catalog/attribute');
        $this->load->model('catalog/attribute_group');

        $attribute_group = $this->model_catalog_attribute_group->getAttributeGroup(1);

        if (!$attribute_group) {
            $attribute_group_description[$language_id] = [
                'name' => 'Свойства',
            ];
            $data = [
                'sort_order'                  => 0,
                'attribute_group_description' => $attribute_group_description,
            ];
            $this->model_catalog_attribute_group->addAttributeGroup($data);
        }
        foreach ($xml as $attribute) {
            $id = (string)$attribute->Ид;
            $name = (string)$attribute->Наименование;
            $values = [];
            if ((string)$attribute->ВариантыЗначений) {
                if ((string)$attribute->ТипЗначений == 'Справочник') {
                    foreach ($attribute->ВариантыЗначений->Справочник as $option_value) {
                        if ((string)$option_value->Значение != '') {
                            $values[(string)$option_value->ИдЗначения] = (string)$option_value->Значение;
                        }
                    }
                }
            }
            $data = [
                'attribute_group_id' => 1,
                'sort_order'         => 0,
            ];
            $data['attribute_description'][$language_id]['name'] = (string)$name;
            // Если атрибут уже был добавлен, то возвращаем старый id, если атрибута нет, то создаем его и возвращаем его id
            $current_attribute = $this->db->query('SELECT attribute_id FROM ' . DB_PREFIX . 'attribute_to_1c WHERE 1c_attribute_id = "' . $id . '"');
            if (!$current_attribute->num_rows) {
                $attribute_id = $this->model_catalog_attribute->addAttribute($data);
                $this->db->query('INSERT INTO `' . DB_PREFIX . 'attribute_to_1c` SET attribute_id = ' . (int)$attribute_id . ', `1c_attribute_id` = "' . $id . '"');
            } else {
                $data = $current_attribute->row;
                $attribute_id = $data['attribute_id'];
            }

            $this->PROPERTIES[$id] = [
                'id'     => $attribute_id,
                'name'   => $name,
                'values' => $values,
            ];

        }

        unset($xml);
    }

    /**
     * Функция работы с продуктом
     *
     * @param array $product
     * @param int   $language_id
     *
     * @internal param $array
     * @return bool
     */
    private function setProduct($product, $language_id)
    {

        if (!$product) return false;

        // Проверяем, связан ли 1c_id с product_id
        $product_id = $this->getProductIdBy1CProductId($product['1c_id']);
        $data = $this->initProduct($product, [], $language_id);
        $this->load->model('catalog/product');
        if ($product_id) {
            $this->updateProduct($product, $product_id, $language_id);
        } else {

            if ($this->config->get('exchange1c_dont_use_artsync') || empty($data['sku'])) {

                $product_id = $this->model_catalog_product->addProduct($data);
            } else {
                // Проверяем, существует ли товар с тем-же артикулом
                // Если есть, то обновляем его
                $product_id = $this->getProductBySKU($data['sku']);

                if ($product_id !== false) {

                    $this->updateProduct($product, $product_id, $language_id);
                } else {
                    // Если нет, то создаем новый
                    $this->model_catalog_product->addProduct($data);
                    $product_id = $this->getProductBySKU($data['sku']);
                }
            }
            // Добавляем линк
            if ($product_id) {
                $this->db->query('INSERT INTO `' . DB_PREFIX . 'product_to_1c` SET product_id = ' . (int)$product_id . ', `1c_id` = "' . $this->db->escape($product['1c_id']) . '"');
            }
        }
        // Устанавливаем SEO URL
        if ($product_id) {
            if ($this->config->get('exchange1c_seo_url')) {
                $this->setSeoURL('product_id', $product_id, $product['name']);
            }
        }
        return true;
    }


    /**
     * Обновляет массив с информацией о продукте
     *
     * @param    array    новые данные
     * @param    array    обновляемые данные
     *
     * @return    array
     */
    private function initProductWithUpdate($update, $product, $data, $field_name, $choice)
    {
        if ($update) {
            if (in_array($field_name, $this->update_product_fields) OR in_array($field_name, $this->update_product_desc_fields)) {
                $rf = (isset($product[$field_name])) ? $product[$field_name] : (isset($data[$field_name]) ? $data[$field_name] : $choice);
                return $rf;
            } else {
                $rf = (isset($data[$field_name])) ? $data[$field_name] : (isset($product[$field_name]) ? $product[$field_name] : $choice);
                return $rf;
            }
        } else {
            $rf = (isset($product[$field_name])) ? $product[$field_name] : (isset($data[$field_name]) ? $data[$field_name] : $choice);
            return $rf;
        }
    }

    private function initProductDescWithUpdate($update, $product, $data, $field_name, $text, $language_id)
    {
        if ($update) {
            if (in_array($field_name, $this->update_product_fields) OR in_array($field_name, $this->update_product_desc_fields)) {
                $rf = (isset($product[$field_name])) ? $product[$field_name] : (isset($data['product_description'][$language_id][$field_name]) ? $data['product_description'][$language_id][$field_name] : $text);
                return $rf;
            } else {
                $rf = (isset($data['product_description'][$language_id][$field_name])) ? $data['product_description'][$language_id][$field_name] : (isset($product[$field_name]) ? $product[$field_name] : $text);
                return $rf;
            }
        } else {
            $rf = (isset($product[$field_name])) ? $product[$field_name] : (isset($data['product_description'][$language_id][$field_name]) ? $data['product_description'][$language_id][$field_name] : $text);
            return $rf;
        }
    }

    private function initProduct($product, $data = [], $language_id, $update = false)
    {
        if ($this->config->get('exchange1c_update_desc_fields')) {
            $this->update_product_desc_fields = json_decode($this->config->get('exchange1c_update_desc_fields'), true);
        } else {
            $this->update_product_desc_fields = [];
        }
        if ($this->config->get('exchange1c_update_fields')) {
            $this->update_product_fields = json_decode($this->config->get('exchange1c_update_fields'), true);
        } else {
            $this->update_product_fields = [];
        }

        $this->load->model('tool/image');


        $result = [
            'product_description' => [],
            'model'               => $this->initProductWithUpdate($update, $product, $data, 'model', ""),
            'sku'                 => $this->initProductWithUpdate($update, $product, $data, 'sku', ""),
            'upc'                 => $this->initProductWithUpdate($update, $product, $data, 'upc', ""),
            'ean'                 => $this->initProductWithUpdate($update, $product, $data, 'ean', ""),
            'jan'                 => $this->initProductWithUpdate($update, $product, $data, 'jan', ""),
            'isbn'                => $this->initProductWithUpdate($update, $product, $data, 'isbn', ""),
            'mpn'                 => $this->initProductWithUpdate($update, $product, $data, 'mpn', ""),
            'location'            => $this->initProductWithUpdate($update, $product, $data, 'location', ""),

            'price'        => $this->initProductWithUpdate($update, $product, $data, 'price', 0),
            'tax_class_id' => $this->initProductWithUpdate($update, $product, $data, 'tax_class_id', 0),
            'quantity'     => $this->initProductWithUpdate($update, $product, $data, 'quantity', 0),
            'minimum'      => $this->initProductWithUpdate($update, $product, $data, 'minimum', 1),
            'subtract'     => $this->initProductWithUpdate($update, $product, $data, 'subtract', 1),
            'shipping'     => $this->initProductWithUpdate($update, $product, $data, 'shipping', 1),

            'stock_status_id'  => $this->config->get('exchange1c_stock_status_id'),
            'date_available'   => date('Y-m-d', time() - 86400),
            'main_category_id' => 0, /*вот тут надо бы разобраться что за главная категория и нах она нужна*/
            'product_store'    => [0],
            'product_option'   => (isset($product['product_option'])) ? $product['product_option'] : (isset($data['product_option']) ? $data['product_option'] : []),
            'preview'          => $this->model_tool_image->resize('no_image.jpg', 100, 100),
            'manufacturer_id'  => (isset($product['manufacturer_id'])) ? $product['manufacturer_id'] : (isset($data['manufacturer_id']) ? $data['manufacturer_id'] : 0),
            'length_class_id'  => (isset($product['length_class_id'])) ? $product['length_class_id'] : (isset($data['length_class_id']) ? $data['length_class_id'] : 1),
            'weight_class_id'  => (isset($product['weight_class_id'])) ? $product['weight_class_id'] : (isset($data['weight_class_id']) ? $data['weight_class_id'] : 1),

            'keyword' => $this->initProductWithUpdate($update, $product, $data, 'keyword', ""),
            'image'   => $this->initProductWithUpdate($update, $product, $data, 'image', ""),
            'length'  => $this->initProductWithUpdate($update, $product, $data, 'length', ""),
            'width'   => $this->initProductWithUpdate($update, $product, $data, 'width', ""),
            'height'  => $this->initProductWithUpdate($update, $product, $data, 'height', ""),

            'status'     => $this->initProductWithUpdate($update, $product, $data, 'status', 1),
            'sort_order' => $this->initProductWithUpdate($update, $product, $data, 'sort_order', 1),
            'points'     => $this->initProductWithUpdate($update, $product, $data, 'points', 0),
            'weight'     => $this->initProductWithUpdate($update, $product, $data, 'weight', 0),
            'cost'       => $this->initProductWithUpdate($update, $product, $data, 'cost', 0),

            'product_image'     => $this->initProductWithUpdate($update, $product, $data, 'product_image', []),
            'product_discount'  => $this->initProductWithUpdate($update, $product, $data, 'product_discount', []),
            'product_special'   => $this->initProductWithUpdate($update, $product, $data, 'product_special', []),
            'product_download'  => $this->initProductWithUpdate($update, $product, $data, 'product_download', []),
            'product_related'   => $this->initProductWithUpdate($update, $product, $data, 'product_related', []),
            'product_attribute' => $this->initProductWithUpdate(true, $product, $data, 'product_attribute', []),
        ];

        if (VERSION == '1.5.3.1') {
            $result['product_tag'] = (isset($product['product_tag'])) ? $product['product_tag'] : (isset($data['product_tag']) ? $data['product_tag'] : []);
        }

        $result['product_description'] = [
            $language_id => [
                'name'             => $this->initProductDescWithUpdate($update, $product, $data, 'name', 'NoName', true, $language_id),
                'seo_h1'           => $this->initProductDescWithUpdate($update, $product, $data, 'seo_h1', '', true, $language_id),
                'seo_title'        => $this->initProductDescWithUpdate($update, $product, $data, 'seo_title', '', true, $language_id),
                'meta_keyword'     => $this->initProductDescWithUpdate($update, $product, $data, 'meta_keyword', '', true, $language_id),
                'meta_description' => $this->initProductDescWithUpdate($update, $product, $data, 'meta_description', '', true, $language_id),
                'description'      => $this->initProductDescWithUpdate($update, $product, $data, 'description', '', true, $language_id),
                'tag'              => $this->initProductDescWithUpdate($update, $product, $data, 'tag', '', true, $language_id),
                'meta_title'       => '',
                'meta_h1'          => '',
            ],
        ];

        if (isset($product['product_option'])) {
            $product['product_option_id'] = '';
            $product['name'] = '';
            if (!empty($product['product_option']) && isset($product['product_option'][0]['type'])) {
                $result['product_option'] = $product['product_option'];
                if (!empty($data['product_option'])) {
                    $result['product_option'][0]['product_option_value'] = array_merge($product['product_option'][0]['product_option_value'], $data['product_option'][0]['product_option_value']);
                }
            } else {
                $result['product_option'] = $data['product_option'];
            }
        } else {
            $product['product_option'] = [];
        }


        if (isset($product['category_1c_id'])) {
            if ($this->config->get('exchange1c_root_category_is_catalog')) {
                $this->writeLog('rootId' . $this->rootId);
                $result['product_category'][] = (int)$this->rootId;
                $result['main_category_id'] = (int)$this->rootId;
            } else {
                if(isset($data['product_category']) && !$this->config->get('exchange1c_flush_category')){
                    $result['product_category'] = $data['product_category'];
                }else{
                    if (is_object($product['category_1c_id'])) {
                        foreach ($product['category_1c_id'] as $category_item) {
                            if (isset($this->CATEGORIES[(string)$category_item])) {
                                $result['product_category'][] = (int)$this->CATEGORIES[(string)$category_item];
                                $result['main_category_id'] = 0;
                            }
                        }
                    } else {
                        $product['category_1c_id'] = (string)$product['category_1c_id'];
                        if (isset($this->CATEGORIES[$product['category_1c_id']])) {
                            $result['product_category'] = [(int)$this->CATEGORIES[$product['category_1c_id']]];
                            $result['main_category_id'] = (int)$this->CATEGORIES[$product['category_1c_id']];
                        } else {
                            $result['product_category'] = isset($data['product_category']) ? $data['product_category'] : [0];
                            $result['main_category_id'] = isset($data['main_category_id']) ? $data['main_category_id'] : 0;
                        }
                    }
                }

            }
        }

        if (!isset($result['product_category']) && isset($data['product_category'])) {
            $result['product_category'] = $data['product_category'];
        }


        if (isset($product['related_options_use'])) {
            $result['related_options_use'] = $product['related_options_use'];
        }
        if (isset($product['related_options_variant_search'])) {
            $result['related_options_variant_search'] = $product['related_options_variant_search'];
        }
        if (isset($product['relatedoptions'])) {
            $result['relatedoptions'] = $product['relatedoptions'];
        }

        return $result;
    }

    /**
     * Обновляет продукт
     *
     * @param array $product
     * @param bool  $product_id
     * @param int   $language_id
     *
     * @internal param $array
     * @internal param $int
     */
    private function updateProduct($product, $product_id = false, $language_id)
    {

        // Проверяем что обновлять?

        if ($this->config->get('exchange1c_relatedoptions')) {
            if ($product_id == false) {
                $this->setProduct($product, $language_id);
                return;
            }
        } else {
            if ($product_id !== false) {
                $product_id = $this->getProductIdBy1CProductId($product['1c_id']);
            }
        }


        // Обновляем описание продукта
        $product_old = $this->getProductWithAllData($product_id);
        // Работаем с ценой на разные варианты товаров.
        if (!empty($product['product_option'][0])) {
            if (isset($product_old['price']) && (float)$product_old['price'] > 0) {
                $price = (float)$product_old['price'] - (float)$product['product_option'][0]['product_option_value'][0]['price'];
                $product['product_option'][0]['product_option_value'][0]['price_prefix'] = ($price > 0) ? '-' : '+';
                $product['product_option'][0]['product_option_value'][0]['price'] = abs($price);
                $product['price'] = (float)$product_old['price'];
            } else {
                $product['product_option'][0]['product_option_value'][0]['price'] = 0;
            }

        }

        $this->load->model('catalog/product');

        $product_old = $this->initProduct($product, $product_old, $language_id, true);
//        $this->log->write($product_old);
        //Редактируем продукт
        $this->model_catalog_product->editProduct($product_id, $product_old);

    }

    /**
     * Функция работы с продуктом
     *
     * @param    int
     *
     * @return    array
     */

    private function getProductWithAllData($product_id)
    {
        $this->load->model('catalog/product');
        $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE p.product_id = '" . (int)$product_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
        $data = [];

        if ($query->num_rows) {
            $data = $query->row;
            $data = array_merge($data, ['product_description' => $this->model_catalog_product->getProductDescriptions($product_id)]);
            $data = array_merge($data, ['product_option' => $this->model_catalog_product->getProductOptions($product_id)]);

            $data['product_image'] = [];

            $results = $this->model_catalog_product->getProductImages($product_id);

            foreach ($results as $result) {
                $data['product_image'][] = [
                    'image'      => $result['image'],
                    'sort_order' => $result['sort_order'],
                ];
            }

            if (method_exists($this->model_catalog_product, 'getProductMainCategoryId')) {
                $data = array_merge($data, ['main_category_id' => $this->model_catalog_product->getProductMainCategoryId($product_id)]);
            }

            $data = array_merge($data, ['product_discount' => $this->model_catalog_product->getProductDiscounts($product_id)]);
            $data = array_merge($data, ['product_special' => $this->model_catalog_product->getProductSpecials($product_id)]);
            $data = array_merge($data, ['product_download' => $this->model_catalog_product->getProductDownloads($product_id)]);
            $data = array_merge($data, ['product_category' => $this->model_catalog_product->getProductCategories($product_id)]);
            $data = array_merge($data, ['product_store' => $this->model_catalog_product->getProductStores($product_id)]);
            $data = array_merge($data, ['product_related' => $this->model_catalog_product->getProductRelated($product_id)]);
            $data = array_merge($data, ['product_attribute' => $this->model_catalog_product->getProductAttributes($product_id)]);

            if (VERSION == '1.5.3.1') {
                $data = array_merge($data, ['product_tag' => $this->model_catalog_product->getProductTags($product_id)]);
            }
        }

        $query = $this->db->query('SELECT * FROM ' . DB_PREFIX . 'url_alias WHERE query LIKE "product_id=' . $product_id . '"');
        if ($query->num_rows) $data['keyword'] = $query->row['keyword'];

        return $data;
    }


    /**
     * Получает product_id из 1c_id
     *
     * @param    string
     *
     * @return    int|bool
     */
    private function getProductIdBy1CProductId($product_id)
    {
        if (is_null($this->PRODUCT_IDS)) {
            $this->PRODUCT_IDS = [];
            $query = $this->db->query('SELECT product_id, 1c_id FROM ' . DB_PREFIX . 'product_to_1c');
            foreach ($query->rows as $product) {
                $this->PRODUCT_IDS[$product['1c_id']] = $product['product_id'];
            }
        }
        return isset($this->PRODUCT_IDS[$product_id]) ? $this->PRODUCT_IDS[$product_id] : false;
    }

    /**
     * Получает путь к картинке и накладывает водяные знаки
     *
     * @param    string
     *
     * @return    string
     */
    private function applyWatermark($filename)
    {
        if (!empty($filename)) {
            $info = pathinfo($filename);
            $wmfile = DIR_IMAGE . $this->config->get('exchange1c_watermark');
            if (is_file($wmfile)) {
                $extension = $info['extension'];
                $minfo = getimagesize($wmfile);
                $image = new Image(DIR_IMAGE . $filename);
                $image->watermark($wmfile, 'center', $minfo['mime']);
                $new_image = utf8_substr($filename, 0, utf8_strrpos($filename, '.')) . '_watermark.' . $extension;
                $image->save(DIR_IMAGE . $new_image);
                return $new_image;
            } else {
                return $filename;
            }
        } else {
            return 'no_image.jpg';
        }
    }

    /**
     * Получает product_id по артикулу
     *
     * @param    string
     *
     * @return    int|bool
     */
    private function getProductBySKU($sku)
    {

        $query = $this->db->query("SELECT product_id FROM `" . DB_PREFIX . "product` WHERE `sku` = '" . $this->db->escape($sku) . "'");

        if ($query->num_rows) {
            return $query->row['product_id'];
        } else {
            return false;
        }
    }

    /**
     * Парсит цены и количество
     *
     * @param    string    наименование типа цены
     */
    public function parseOffers($filename, $config_price_type, $language_id)
    {
        $this->enable_log = $this->config->get('exchange1c_full_log');
        $importFile = DIR_CACHE . 'exchange1c/' . $filename;
        $xml = simplexml_load_file($importFile);
        $price_types = [];
        $config_price_type_main = [];
        $exchange1c_relatedoptions = $this->config->get('exchange1c_relatedoptions');

        $this->load->model('catalog/option');

        $this->writeLog("Начат разбор файла: " . $filename);

        if (!empty($config_price_type) && count($config_price_type) > 0) {
            $config_price_type_main = array_shift($config_price_type);
        }


        if ($xml->ПакетПредложений->ТипыЦен->ТипЦены) {
            foreach ($xml->ПакетПредложений->ТипыЦен->ТипЦены as $key => $type) {
                $price_types[(string)$type->Ид] = (string)$type->Наименование;
                if ($key == 0 && !isset($config_price_type_main)) {
                    $config_price_type_main['keyword'] = (string)$type->Наименование;
                }
            }
        }

        // Инициализация массива скидок для оптимизации алгоритма
        if (!empty($config_price_type) && count($config_price_type) > 0) {
            $discount_price_type = [];
            foreach ($config_price_type as $obj) {
                $discount_price_type[$obj['keyword']] = [
                    'customer_group_id' => $obj['customer_group_id'],
                    'quantity'          => $obj['quantity'],
                    'priority'          => $obj['priority'],
                ];
            }
        }

        $offer_cnt = 0;

        if ($xml->ПакетПредложений->Предложения->Предложение) {
            foreach ($xml->ПакетПредложений->Предложения->Предложение as $offer) {

                $new_product = (!isset($data));

                $offer_cnt++;

                if (!$exchange1c_relatedoptions || $new_product) {

                    $data = [];
                    $data['price'] = 0;

                    //UUID без номера после #
                    $uuid = explode("#", $offer->Ид);
                    $data['1c_id'] = $uuid[0];
                    $this->writeLog("Товар: [UUID]:" . $data['1c_id']);
                    $product_id = $this->getProductIdBy1CProductId($uuid[0]);
                    $this->writeLog("Товар: product_id:" . $product_id);
                    //Цена за единицу
                    if ($offer->Цены) {

                        // Первая цена по умолчанию - $config_price_type_main
                        if (!$config_price_type_main['keyword']) {
                            $data['price'] = (float)$offer->Цены->Цена->ЦенаЗаЕдиницу;
                            $this->writeLog("Первая цена по умолчанию " . $data['price']);
                        } else {
                            if ($offer->Цены->Цена->ИдТипаЦены) {
                                foreach ($offer->Цены->Цена as $price) {
                                    if ($price_types[(string)$price->ИдТипаЦены] == $config_price_type_main['keyword']) {
                                        $data['price'] = (float)$price->ЦенаЗаЕдиницу;
                                        $this->writeLog(" найдена цена  > " . $data['price']);
                                    }
                                }
                            }
                        }
                        // Вторая цена и тд - $discount_price_type
                        if (!empty($discount_price_type) && $offer->Цены->Цена->ИдТипаЦены) {
                            foreach ($offer->Цены->Цена as $price) {
                                $key = $price_types[(string)$price->ИдТипаЦены];
                                if (isset($discount_price_type[$key])) {
                                    $value = [
                                        'customer_group_id' => $discount_price_type[$key]['customer_group_id'],
                                        'quantity'          => $discount_price_type[$key]['quantity'],
                                        'priority'          => $discount_price_type[$key]['priority'],
                                        'price'             => (float)$price->ЦенаЗаЕдиницу,
                                        'date_start'        => '0000-00-00',
                                        'date_end'          => '0000-00-00',
                                    ];
                                    $data['product_discount'][] = $value;
                                    unset($value);
                                }
                            }
                        }
                    }
                    //Количество
                    $data['quantity'] = isset($offer->Количество) ? (int)$offer->Количество : 0;
                }

                //Характеристики
                if ($offer->ХарактеристикиТовара->ХарактеристикаТовара) {

                    $product_option_value_data = [];
                    $product_option_data = [];

                    $lang_id = (int)$this->config->get('config_language_id');
                    $count = count($offer->ХарактеристикиТовара->ХарактеристикаТовара);

                    foreach ($offer->ХарактеристикиТовара->ХарактеристикаТовара as $i => $opt) {
                        $name_1c = (string)$opt->Наименование;
                        $value_1c = (string)$opt->Значение;
                        if (!empty($name_1c) && !empty($value_1c)) {

                            if ($exchange1c_relatedoptions) {
                                $uuid = explode("#", $offer->Ид);
                                if (!isset($char_id) || $char_id != $uuid[1]) {
                                    $char_id = $uuid[1];
                                    $this->writeLog("Характеристика: " . $char_id);
                                }
                            }
                            $this->writeLog(" Найдены характеристики: " . $name_1c . " -> " . $value_1c);

                            $option_id = $this->setOption($name_1c);

                            $option_value_id = $this->setOptionValue($option_id, $value_1c);

                            $product_option_value_data[] = [
                                'option_value_id'         => (int)$option_value_id,
                                'product_option_value_id' => '',
                                'quantity'                => isset($data['quantity']) ? (int)$data['quantity'] : 0,
                                'subtract'                => 0,
                                'price'                   => isset($data['price']) ? (int)$data['price'] : 0,
                                'price_prefix'            => '+',
                                'points'                  => 0,
                                'points_prefix'           => '+',
                                'weight'                  => 0,
                                'weight_prefix'           => '+',
                            ];

                            $product_option_data[] = [
                                'product_option_id'    => '',
                                'name'                 => (string)$name_1c,
                                'option_id'            => (int)$option_id,
                                'type'                 => 'select',
                                'required'             => 1,
                                'product_option_value' => $product_option_value_data,
                            ];

                            if ($exchange1c_relatedoptions) {

                                if (!isset($data['relatedoptions'])) {
                                    $data['relatedoptions'] = [];
                                    $data['related_options_variant_search'] = TRUE;
                                    $data['related_options_use'] = TRUE;
                                }

                                $ro_found = FALSE;
                                foreach ($data['relatedoptions'] as $ro_num => $relatedoptions) {
                                    if ($relatedoptions['char_id'] == $char_id) {
                                        $data['relatedoptions'][$ro_num]['options'][$option_id] = $option_value_id;
                                        $ro_found = TRUE;
                                        break;
                                    }
                                }
                                if (!$ro_found) {
                                    $data['relatedoptions'][] = ['char_id' => $char_id, 'quantity' => (isset($offer->Количество) ? (int)$offer->Количество : 0), 'options' => [$option_id => $option_value_id]];
                                }

                            } else {
                                $data['product_option'] = $product_option_data;
                            }
                        }
                    }
                }

                if (!$exchange1c_relatedoptions || $new_product) {

                    if ($offer->СкидкиНаценки) {
                        $value = [];
                        foreach ($offer->СкидкиНаценки->СкидкаНаценка as $discount) {
                            $value = [
                                'customer_group_id' => 1
                                , 'priority'        => isset($discount->Приоритет) ? (int)$discount->Приоритет : 0
                                , 'price'           => (int)(($data['price'] * (100 - (float)str_replace(',', '.', (string)$discount->Процент))) / 100)
                                , 'date_start'      => isset($discount->ДатаНачала) ? (string)$discount->ДатаНачала : ''
                                , 'date_end'        => isset($discount->ДатаОкончания) ? (string)$discount->ДатаОкончания : ''
                                , 'quantity'        => 0,
                            ];

                            $data['product_discount'][] = $value;

                            if ($discount->ЗначениеУсловия) {
                                $value['quantity'] = (int)$discount->ЗначениеУсловия;
                            }

                            unset($value);
                        }
                    }

                    $data['status'] = 1;
                }

                if (!$exchange1c_relatedoptions || $offer_cnt == count($xml->ПакетПредложений->Предложения->Предложение)
                    || $data['1c_id'] != substr($xml->ПакетПредложений->Предложения->Предложение[$offer_cnt]->Ид, 0, strlen($data['1c_id']))
                ) {

                    $this->updateProduct($data, $product_id, $language_id);
                    unset($data);
                }


            }
        }

        $this->cache->delete('product');

        $this->writeLog("Окончен разбор файла: " . $filename);
    }

    private function setOption($name)
    {
        $lang_id = (int)$this->config->get('config_language_id');

        $query = $this->db->query("SELECT option_id FROM " . DB_PREFIX . "option_description WHERE name='" . $this->db->escape($name) . "'");

        if ($query->num_rows > 0) {
            $option_id = $query->row['option_id'];
        } else {
            //Нет такой опции
            $this->db->query("INSERT INTO `" . DB_PREFIX . "option` SET type = 'select', sort_order = '0'");
            $option_id = $this->db->getLastId();
            $this->db->query("INSERT INTO " . DB_PREFIX . "option_description SET option_id = '" . $option_id . "', language_id = '" . $lang_id . "', name = '" . $this->db->escape($name) . "'");
        }
        return $option_id;
    }

    private function setOptionValue($option_id, $value)
    {
        $lang_id = (int)$this->config->get('config_language_id');

        $query = $this->db->query("SELECT option_value_id FROM " . DB_PREFIX . "option_value_description WHERE name='" . $this->db->escape($value) . "' AND option_id='" . $option_id . "'");

        if ($query->num_rows > 0) {
            $option_value_id = $query->row['option_value_id'];
        } else {
            //Добавляем значение опции, только если нет в базе
            $this->db->query("INSERT INTO " . DB_PREFIX . "option_value SET option_id = '" . $option_id . "', image = '', sort_order = '0'");
            $option_value_id = $this->db->getLastId();
            $this->db->query("INSERT INTO " . DB_PREFIX . "option_value_description SET option_value_id = '" . $option_value_id . "', language_id = '" . $lang_id . "', option_id = '" . $option_id . "', name = '" . $this->db->escape($value) . "'");
        }
        return $option_value_id;
    }

    /**
     * Заполняет продуктами родительские категории
     */
    public function fillParentsCategories()
    {
        $this->load->model('catalog/product');
//        if (!method_exists($this->model_catalog_product, 'getProductMainCategoryId')) {
//            $this->log->write("  !!!: Заполнение родительскими категориями отменено. Отсутствует main_category_id.");
//            return;
//        }

        $this->log->write("fillParentsCategories start");
        $this->db->query('DELETE FROM `' . DB_PREFIX . 'product_to_category` WHERE `main_category` = 0');
        $query = $this->db->query('SELECT * FROM `' . DB_PREFIX . 'product_to_category` WHERE `main_category` = 1');

        if ($query->num_rows) {
            foreach ($query->rows as $row) {
                $parents = $this->findParentsCategories($row['category_id']);
                foreach ($parents as $parent) {
                    if ($row['category_id'] != $parent && $parent != 0) {
                        $this->db->query('INSERT INTO `' . DB_PREFIX . 'product_to_category` SET `product_id` = ' . $row['product_id'] . ', `category_id` = ' . $parent . ', `main_category` = 0');
                    }
                }
            }
        }
        $this->log->write("fillParentsCategories end");
    }

    /**
     * Ищет все родительские категории
     *
     * @param    int
     *
     * @return    array
     */
    private function findParentsCategories($category_id)
    {
        $query = $this->db->query('SELECT * FROM `' . DB_PREFIX . 'category` WHERE `category_id` = "' . $category_id . '"');
        if (isset($query->row['parent_id'])) {
            $result = $this->findParentsCategories($query->row['parent_id']);
        }
        $result[] = $category_id;
        return $result;
    }

    public function getColumnsName($table_name)
    {
        $text = "SELECT `COLUMN_NAME` FROM `INFORMATION_SCHEMA`.`COLUMNS` WHERE `TABLE_NAME`='" . DB_PREFIX . $table_name . "'";
        $query = $this->db->query($text);
        $data = [];
        $no_check_fields = ['tax_class_id', 'product_id', 'stock_status_id', 'manufacturer_id', 'weight_class_id', 'length_class_id', 'viewed', 'date_added', 'date_modified', 'date_available', 'language_id'];
        foreach ($query->rows as $item) {
            if (!in_array($item["COLUMN_NAME"], $no_check_fields)) {
                $data[] = $item["COLUMN_NAME"];
            }
        }
        return $data;
    }

    public function deleteProductAfter($product_id)
    {
        $this->enable_log = $this->config->get('exchange1c_full_log');
        $this->writeLog('model deleteProductAfter');
        $text = "DELETE FROM `" . DB_PREFIX . "product_to_1c` WHERE `product_id` = '" . $product_id . "'";
        $this->db->query($text);
        $this->writeLog($text);
    }
    public function deleteCategoryAfter($category_id)
    {
        $this->enable_log = $this->config->get('exchange1c_full_log');
        $this->writeLog('model deleteCategoryAfter');
        $text = "DELETE FROM `" . DB_PREFIX . "category_to_1c` WHERE `category_id` = '" . $category_id . "'";
        $this->db->query($text);
        $this->writeLog($text);
    }

}