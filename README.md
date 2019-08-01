# Excel Upload Demo
http://3x.ralphharris3.com (username: neo / password: neo)

## Whats included
1. Site created with Yii 2 Framework
2. Uses: https://canvasjs.com/ for charts
3. Uses: https://github.com/amnah/yii2-user for user authentication (bcrypt encryption).

## Files Modified for this project (documentation is in each file where appropriate)
```
src/controllers/ExcelUploadController.php - controller for the excel file upload
src/models/forms/ExcelUploadForm.php - form model for the file upload
src/views/excel-upload/form.php - view for the file upload and chart display
src/services/ParseExcelFile.php - handles the excel file parsing
src/views/layouts/main.php - modified default login
