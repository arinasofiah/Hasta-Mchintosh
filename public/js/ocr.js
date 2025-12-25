let icVerified = false;

function upload(file, url, callback) {
  const data = new FormData();
  data.append('file', file);

  fetch(url, {
    method: 'POST',
    headers: {
      'X-CSRF-TOKEN': document.querySelector('input[name=_token]').value
    },
    body: data
  })
  .then(res => res.json())
  .then(callback);
}

document.getElementById('icUpload').addEventListener('change', e => {
  upload(e.target.files[0], '/ocr/ic', data => {
    document.getElementById('fullName').value = data.name;
    document.getElementById('icNumber').value = data.ic;
    icVerified = true;
    document.getElementById('submitBtn').disabled = false;
  });
});

document.getElementById('licenseUpload').addEventListener('change', e => {
  upload(e.target.files[0], '/ocr/license', data => {
    document.getElementById('licenseNumber').value = data.license;
  });
});

document.getElementById('studentUpload').addEventListener('change', e => {
  upload(e.target.files[0], '/ocr/student', data => {
    document.getElementById('studentNumber').value = data.student;
  });
});
