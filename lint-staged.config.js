export default {
  '{src,tests}/**/*.php': () => [
    'composer test-coverage',
    'composer type-coverage',
    'composer analyse',
    'composer format'
  ]
}