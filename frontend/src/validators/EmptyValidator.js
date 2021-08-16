class EmptyValidator {
  validate(value) {
    return value !== '' && value !== undefined && value !== null;
  }
  message() {
    return 'This field is required';
  }
}
export default new EmptyValidator();