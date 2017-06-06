module.exports = {
	default: {
		src:  [
			'**',
			'!.git/**',
			'!.gitignore',
			'!bin/**',
			'!composer.json',
			'!composer.lock',
			'!grunt/**',
			'!Gruntfile.*',
			'!node_modules/**',
			'!package.json',
			'!package-lock.json',
			'!phpcs.ruleset.xml',
			'!README.md',
			'!release/**'
		],
		dest: 'release/<%= package.version %>/'
	}
};
