class NumericValidator {
  validate(value) {
    if (!value) return false;
    value = String(value);
    return value.match(new RegExp("^[0-9]+$"));
  }
  message() {
    return 'This field must be numeric';
  }
}
export default new NumericValidator();