import { ENTRYPOINT } from '../config/entrypoint';
import { SubmissionError } from 'redux-form';

const MIME_TYPE = 'application/ld+json';

export function fetch(id, options = {}) {
  if ('undefined' === typeof options.headers) options.headers = new Headers();
  if (null === options.headers.get('Accept'))
    options.headers.set('Accept', MIME_TYPE);

  if (
    'undefined' !== options.body &&
    !(options.body instanceof FormData) &&
    null === options.headers.get('Content-Type')
  )
    options.headers.set('Content-Type', MIME_TYPE);

  return global.fetch(new URL(id, ENTRYPOINT), options).then(response => {
    if (response.ok) return response;

    return response.json().then(
      json => {
        const error =
          json['hydra:description'] ||
          json['hydra:title'] ||
          'An error occurred.';
        if (!json.violations) throw Error(error);

        let errors = { _error: error };
        json.violations.forEach(violation =>
          errors[violation.propertyPath]
            ? (errors[violation.propertyPath] +=
                '\n' + errors[violation.propertyPath])
            : (errors[violation.propertyPath] = violation.message)
        );

        throw new SubmissionError(errors);
      },
      () => {
        throw new Error(response.statusText || 'An error occurred.');
      }
    );
  });
}