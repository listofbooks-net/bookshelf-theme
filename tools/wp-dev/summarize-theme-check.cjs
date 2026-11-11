// Reads `wp theme-check run --format=json` on stdin, prints each finding and a
// summary, and exits 1 when Theme Check reports any REQUIRED or WARNING item.

const TYPES = ['REQUIRED', 'WARNING', 'RECOMMENDED', 'INFO'];
const BLOCKING = ['REQUIRED', 'WARNING'];

let input = '';
process.stdin.setEncoding('utf8');
process.stdin.on('data', (chunk) => (input += chunk));
process.stdin.on('end', () => {
	let findings;
	try {
		findings = JSON.parse(input);
	} catch (error) {
		console.error(`Theme Check did not return JSON: ${error.message}`);
		process.exit(2);
	}
	for (const finding of findings) console.log(finding.type.padEnd(12), finding.value.replace(/<[^>]+>/g, ''));
	const counts = Object.fromEntries(TYPES.map((type) => [type, findings.filter((f) => f.type === type).length]));
	console.log(TYPES.map((type) => `${counts[type]} ${type}`).join(', '));
	process.exit(BLOCKING.some((type) => counts[type] > 0) ? 1 : 0);
});
