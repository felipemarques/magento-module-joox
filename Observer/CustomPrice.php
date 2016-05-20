<?php namespace FelipeMarques\JooxMakerTool\Observer;

class CustomPrice implements \Magento\Framework\Event\ObserverInterface
{

    const DS = DIRECTORY_SEPARATOR;

    protected $_logger;
    protected $_request;
    protected $_context;
    protected $_directory_list;

    public function __construct(
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\App\RequestInterface $requestInterface,
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\App\Filesystem\DirectoryList $directory_list
    ){
        $this->_logger = $logger;
        $this->_request = $requestInterface;
        $this->_context = $context;
        $this->_directory_list = $directory_list;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $eventName = $observer->getEvent()->getName();
        $this->_logger->info(__METHOD__ . '[OBSERVER EVENT CATCHED]: ' . $eventName);
        //$this->_logger->info("_POST: " . print_r($_POST, true));

        if (isset($_POST['joox']) && isset($_POST['joox']['creation']) && $_POST['joox']['creation']['totalPrice'] > 0) {
            $item = $observer->getEvent()->getData('quote_item');
            $item = ($item->getParentItem() ? $item->getParentItem() : $item);

            if ($item instanceof \Magento\Quote\Model\Quote\Item) {

                $price = floatval($_POST['joox']['creation']['totalPrice']); //set your price here
                $creationId = $_POST['joox']['creation']['id'];

                //$jooxCustomId = 'joox_custom_'.time();
                $jooxCustomId = 'joox_custom';

                $item->setCustomPrice($price);
                $item->setOriginalCustomPrice($price);
                $item->setDescription('teste felipe description');

                $product = $item->getProduct();
                $product->setName($product->getName() . ' (JOOX)');
                $product->addCustomOption($jooxCustomId, serialize(['creation_id' => 654654654654]));

                $product->setMetaKeyword('testproduct')
                        ->setMetaDescription('test meta description')
                        ->setDescription('This is a long description')
                        ->setShortDescription('This is a short description');

                $product->setIsSuperMode(true);

                //|------------------------------
                //| Additional Options
                //|------------------------------
                $additionalOptions = array();
                $additionalOptions[] = array(
                     'label' => 'Joox Creation ID',
                     'value' => $creationId
                );
                $product->addCustomOption('additional_options', serialize($additionalOptions));

                //|------------------------------
                //| Custom image
                //|------------------------------
                $image_url  = 'https://placeholdit.imgix.net/~text?txtsize=13&txt=88x110&w=88&h=110'; //get external image url from csv
                $image_type = '.jpg';
                $filename   = md5($image_url . $creationId . time() . uniqid()) . '.' . $image_type; //give a new name, you can modify as per your requirement
                $filepath   = $this->_directory_list->getPath('media') . self::DS . 'import' . self::DS . $filename; //path for temp storage folder: ./media/import/
                file_put_contents($filepath, file_get_contents(trim($image_url))); //store the image from external url to the temp storage folder
                $mediaAttribute = array (
                    'thumbnail',
                    'small_image',
                    'image'
                );
                $product->addImageToMediaGallery($filepath, $mediaAttribute, false, false);

                //$product->save();

                $item->addOption($product->getCustomOption($jooxCustomId));

            }
        }
    }
}