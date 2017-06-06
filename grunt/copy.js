module.exports = {
	default: {
		src:  [
			'**',
			'!.git/**',
			'!.gitignore',
			'!bin/**',
			'!composer.lock',
			'!grunt/**',
			'!Gruntfile.*',
			'!node_modules/**',
			'!package.json',
			'!package-lock.json',
			'!README.md',
			'!release/**'
		],
		dest: 'release/<%= package.version %>/'
	}
};
