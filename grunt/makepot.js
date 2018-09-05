module.exports = {
	target: {
		options: {
			mainFile: 'backupwordpress.php',
			potFilename: 'backupwordpress.pot',
			domainPath: '/languages',       // Where to save the POT file.
			exclude: ['node_modules/.*','vendor/.*', 'backdrop/.*','bin/.*','tests/.*','readme/.*','languages/.*', 'releases/.*'],
			mainFile  : 'backupwordpress.php',         // Main project file.
			type      : 'wp-plugin',    // Type of project (wp-plugin or wp-theme).
			processPot: function( pot, options ) {
				pot.headers['report-msgid-bugs-to'] = 'support@xibomarketing.com';
				pot.headers['last-translator'] = 'XIBO Ltd';
				pot.headers['language-team'] = 'XIBO Ltd';
				return pot;
			}
		}
	}
};
