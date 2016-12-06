
      google.load("elements", "1", {
            packages: "transliteration"
          });
      function onLoad() {
        var options = {
            sourceLanguage:
                google.elements.transliteration.LanguageCode.ENGLISH,
            destinationLanguage:
                google.elements.transliteration.LanguageCode.GUJARATI,
            shortcutKey: 'ctrl+g',
            transliterationEnabled: true
        };
        var control =
            new google.elements.transliteration.TransliterationControl(options);
         control.makeTransliteratable(['transliterateTextarea']);
		 control.makeTransliteratable(['transliterateTextarea2']);
		 control.makeTransliteratable(['transliterateTextarea3']);
		 control.makeTransliteratable(['transliterateTextarea4']);
		 
      }
      google.setOnLoadCallback(onLoad);


