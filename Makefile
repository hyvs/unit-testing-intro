install:
	composer install
	cd slides && npm install -g prez

prez:
	cd slides && prez --serve

test_1:
	vendor/bin/phpunit test/1-first-test/GrootTest.php

test_2:
	vendor/bin/phpunit test/2-mock-prophecy/PriceCalculatorTest.php

test_3:
	vendor/bin/phpunit test/3-test-exception/PriceCalculatorTest.php

test_4:
	vendor/bin/phpunit test/4-data-provider/PriceCalculatorTest.php

test_5:
	vendor/bin/phpunit test/5-complex-test/VoucherFillerTest.php
