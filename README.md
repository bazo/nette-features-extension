Nette Feature Toggler Extension
===============

Feature toggling library for php

usage:

register extension

````
extensions:
	features: Bazo\FeatureToggler\DI\FeaturesExtension
````

configure features, for more information see https://github.com/bazo/feature-toggler#feature-toggler
````
features:
	features:
		globals:
			ip: %remoteIp%
		analytics:
			conditions:
				- {ip, in, %remoteIps%}
		zopim:
			conditions:
				- {ip, in, %remoteIps%}
		facebook-like:
			conditions:
				- {ip, in, %remoteIps%}
		login:
			conditions:
				- {ip, in, %allowedIps%}
		registration:
			conditions:
				- {ip, in, %allowedIps%}
		membership:
			active: FALSE
````

in latte you can use macros:
````
{ifEnabled feature $context} or {ifEnabled feature [username => bazo]}
feature is enabled
{else}
not enabled
{/ifEnabled}
````

or n-macros:
````
<div n:ifEnabled="feature $context">
feature is enabled
</div>
````

there's also the not enabled alternative ifNotEnabled