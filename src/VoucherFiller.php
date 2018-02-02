<?php

use Symfony\Component\HttpFoundation\Request;

class VoucherFiller
{
    private $pictureUploader;

    public function __construct(PictureUploaderInterface $pictureUploader)
    {
        $this->pictureUploader = $pictureUploader;
    }

    public function fillVoucher(Voucher $voucher, int $shopId, Request $request): Voucher
    {
        $title = $request->get('title');
        $shortDescription = $request->get('shortDescription');
        $longDescription = (string) $request->get('longDescription', '');
        $availability = $request->get('availability');
        $pictureName = $request->get('pictureName');
        $pictureData = $request->get('pictureData');
        if (empty($title)
            || empty($shortDescription)
            || !isset($availability['type'], $availability['begin'], $availability['end'], $availability['quantity'])
        ) {
            throw new \Exception('missing parameters');
        }
        $picturePath = '';
        if (!empty($pictureName) && !empty($pictureData)) {
            $picturePath = 'offers/' . $shopId . '-' . uniqid() . '-' . $pictureName;
            $this->pictureUploader->put($picturePath, base64_decode($pictureData));
            $picturePath = $this->pictureBaseUrl . '/' . $picturePath;
        }
        $voucherAvailability = new VoucherAvailability($availability['type']);
        $voucher->setTitle($title);
        $voucher->setDescription($shortDescription);
        $voucher->setLongDescription($longDescription);
        $voucher->setPicture($picturePath);
        $voucher->setType($voucherAvailability->toLegacyType());
        $begin = $availability['begin'] ? new \DateTimeImmutable($availability['begin']) : null;
        $end = $availability['end'] ? new \DateTimeImmutable($availability['end']) : null;
        $voucher->setBegin($begin);
        $voucher->setEnd($end);
        $quantity = (int) ($availability['quantity'] ?? 0);
        $voucher->setQuantity($quantity);
        return $voucher;
    }
}

interface PictureUploaderInterface
{
    public function put($path, $data);
}

class Voucher
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="titre")
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description")
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="description_longue")
     */
    private $longDescription;

    /**
     * @var string
     *
     * @ORM\Column(name="is_limited_time_or_quantity")
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="image_path")
     */
    private $picture;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $quantity;

    /**
     * @var \DateTimeInterface|null
     *
     * @ORM\Column(name="validite_start_time", type="int_timestamp")
     */
    private $begin;

    /**
     * @var \DateTimeInterface|null
     *
     * @ORM\Column(name="validite_time", type="int_timestamp")
     */
    private $end;

    /**
     * @var Shop
     *
     * @ORM\ManyToOne(targetEntity="Shop")
     * @ORM\JoinColumn(name="id_commerce", referencedColumnName="id")
     */
    private $shop;

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getShop(): Shop
    {
        return $this->shop;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getLongDescription(): string
    {
        return $this->longDescription;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getPicture(): string
    {
        return $this->picture;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getBegin(): ?\DateTimeInterface
    {
        return $this->begin;
    }

    public function getEnd(): ?\DateTimeInterface
    {
        return $this->end;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function setLongDescription(string $longDescription): self
    {
        $this->longDescription = $longDescription;

        return $this;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function setPicture(string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function setBegin(?\DateTimeInterface $begin): self
    {
        $this->begin = $begin;

        return $this;
    }

    public function setEnd(?\DateTimeInterface $end): self
    {
        $this->end = $end;

        return $this;
    }

    public function setShop(Shop $shop): self
    {
        $this->shop = $shop;

        return $this;
    }
}

/**
 * @method static VoucherAvailability ALWAYS()
 * @method static VoucherAvailability DATE_RANGE()
 * @method static VoucherAvailability QUANTITY()
 */
final class VoucherAvailability extends Enum
{
    public const ALWAYS = 'always';
    public const DATE_RANGE = 'date_range';
    public const QUANTITY = 'quantity';

    public static function createFromLegacyType(string $legacyType): self
    {
        switch ($legacyType) {
            case 'time':
                return self::DATE_RANGE();
            case 'quantity':
                return self::QUANTITY();
        }
        return self::ALWAYS();
    }

    public function toLegacyType(): string
    {
        if ($this->equals(self::DATE_RANGE())) {
            return 'time';
        }
        if ($this->equals(self::QUANTITY())) {
            return 'quantity';
        }
        return 'always';
    }
}

/**
 * Base Enum class
 *
 * Create an enum by implementing this class and adding class constants.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 * @author Daniel Costa <danielcosta@gmail.com>
 * @author Mirosław Filip <mirfilip@gmail.com>
 */
abstract class Enum
{
    /**
     * Enum value
     *
     * @var mixed
     */
    protected $value;

    /**
     * Store existing constants in a static cache per object.
     *
     * @var array
     */
    protected static $cache = array();

    /**
     * Creates a new value of some type
     *
     * @param mixed $value
     *
     * @throws \UnexpectedValueException if incompatible type is given.
     */
    public function __construct($value)
    {
        if (!$this->isValid($value)) {
            throw new \UnexpectedValueException("Value '$value' is not part of the enum " . get_called_class());
        }

        $this->value = $value;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Returns the enum key (i.e. the constant name).
     *
     * @return mixed
     */
    public function getKey()
    {
        return static::search($this->value);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->value;
    }

    /**
     * Compares one Enum with another.
     *
     * This method is final, for more information read https://github.com/myclabs/php-enum/issues/4
     *
     * @return bool True if Enums are equal, false if not equal
     */
    final public function equals(Enum $enum)
    {
        return $this->getValue() === $enum->getValue() && get_called_class() == get_class($enum);
    }

    /**
     * Returns the names (keys) of all constants in the Enum class
     *
     * @return array
     */
    public static function keys()
    {
        return array_keys(static::toArray());
    }

    /**
     * Returns instances of the Enum class of all Enum constants
     *
     * @return static[] Constant name in key, Enum instance in value
     */
    public static function values()
    {
        $values = array();

        foreach (static::toArray() as $key => $value) {
            $values[$key] = new static($value);
        }

        return $values;
    }

    /**
     * Returns all possible values as an array
     *
     * @return array Constant name in key, constant value in value
     */
    public static function toArray()
    {
        $class = get_called_class();
        if (!array_key_exists($class, static::$cache)) {
            $reflection            = new \ReflectionClass($class);
            static::$cache[$class] = $reflection->getConstants();
        }

        return static::$cache[$class];
    }

    /**
     * Check if is valid enum value
     *
     * @param $value
     *
     * @return bool
     */
    public static function isValid($value)
    {
        return in_array($value, static::toArray(), true);
    }

    /**
     * Check if is valid enum key
     *
     * @param $key
     *
     * @return bool
     */
    public static function isValidKey($key)
    {
        $array = static::toArray();

        return isset($array[$key]);
    }

    /**
     * Return key for value
     *
     * @param $value
     *
     * @return mixed
     */
    public static function search($value)
    {
        return array_search($value, static::toArray(), true);
    }

    /**
     * Returns a value when called statically like so: MyEnum::SOME_VALUE() given SOME_VALUE is a class constant
     *
     * @param string $name
     * @param array  $arguments
     *
     * @return static
     * @throws \BadMethodCallException
     */
    public static function __callStatic($name, $arguments)
    {
        $array = static::toArray();
        if (isset($array[$name])) {
            return new static($array[$name]);
        }

        throw new \BadMethodCallException("No static method or enum constant '$name' in class " . get_called_class());
    }
}
