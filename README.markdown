# Kohana-Maintenance

This module provides a way to schedule maintenance windows within which the
server will generate 503 errors and display a configurable message.


## Installation

Step 1: Download the module into your modules subdirectory.

Step 2: Enable the module in your bootstrap file:

	/**
	 * Enable modules. Modules are referenced by a relative or absolute path.
	 */
	Kohana::modules(array(
		'maintenance'       => MODPATH.'maintenance', // Maintenance windows
		// 'auth'       => MODPATH.'auth',       // Basic authentication
		// 'codebench'  => MODPATH.'codebench',  // Benchmarking tool
		// 'database'   => MODPATH.'database',   // Database access
                ...


Step 3: Copy the file `maintenance/config/maintenance.php` to
`application/config/maintenance.php` and change the values as explained below in
the Usage section.

Step 4: Call `Maintenance::check()` wherever required, for example in the `before()`
method of your custom `Controller` ([explained here][2]). If the server is
currently within a configured maintenance window, then an `HTTP_Exception_503`
will be thrown. You can let your normal error handling catch that, or intercept
it with a `try {} catch () {}` block.

Note: While there are probably ways to get this module to do the check
automatically, but sometimes you might not want to conduct this check - e.g.
Administrators might need to log in still. So this seems to be the most flexible
way to do it.


## Usage

Set one or more maintenance windows in the config file as in the example below.

    return array
        (
        'windows'=>array(
            array(
                'from' => '2012-06-18 20:00:00 UTC',
                'to' => '2012-06-18 21:10:00 UTC',
                'message' => 'This site is undergoing scheduled maintenance from :from until :to and is unavailable during that time.'
            )
        ),
    );

The `from` and `to` times are interpreted using DateTime, so the [rules and
formats given here][1] apply - note that if a timezone isn't specified then the
default PHP timezone (usually the server timezone) is used.

Strings `:from` and `:to` are replaced in the `message` with the respective
times. See Advanced Usage below for more info on the format and timezone.

## Advanced Usage

Normally the `:from` and `:to` parameters are re-interpreted into UTC and are
given an international-style format (specifically: `Y-m-d H:i e`).

However, `check()` has two optional string parameters - `$display_timezone` and
`$display_format`. Using these it is possible to override the specific timezone
or format according to your needs, for example to give the time in the local
timezone of the user.

  [1]: http://www.php.net/manual/en/datetime.construct.php
  [2]: http://stackoverflow.com/questions/3172869/kohana-3-using-a-custom-controller
