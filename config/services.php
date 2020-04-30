<?php

$container->setParameter('mailer_disable_delivery', (bool) getenv('MAILER_DISABLE_DELIVERY'));

$container->setParameter('profiler_toolbar_enable   d', ! getenv('PROFILER_DISABLE_TOOLBAR'));