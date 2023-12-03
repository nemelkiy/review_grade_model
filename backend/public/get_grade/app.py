from flask import Flask, request

import requests

import joblib
import pandas as pd
import nltk
import pickle

from nltk.corpus import stopwords
from nltk.stem.porter import PorterStemmer

from sklearn.model_selection import train_test_split
from sklearn.model_selection import GridSearchCV
from sklearn.pipeline import Pipeline
from sklearn.linear_model import LogisticRegression
from sklearn.feature_extraction.text import TfidfVectorizer
import re

app = Flask(__name__)


def tokenizer(text):
    return text.split()


porter = PorterStemmer()  # выборка до корневой формы


def tokenizer_porter(text):
    return [porter.stem(word) for word in text.split()]


filename = 'grid_mdl.sav'


@app.route('/get_grade')
def model_result():
    review = request.args.get('review')
    clf = joblib.load(filename)
    res = clf.predict([review])
    return str(res[0])


@app.route('/teach_model')
def model_learning():
    nltk.download('stopwords')
    nltk.download('wordnet')

    # Объявим список стоп слов, подготовленных ранее. В основном это притяжительные местоимения (Your, yours, my etc.)
    stop_words = stopwords.words('english')

    # Загрузка датасета для обучения модели
    ds_data = pd.read_csv('dl_reviews.csv', header=None, names=['Grade', 'Review'])

    # Очистка данных от лишних символов

    def preprocessor(text):
        text = re.sub('<[^>]*>', '', text)
        emoticons = re.findall('(?::|;|=)(?:-)?(?:\)|\(|D|P)', text)
        text = (re.sub('[\W]+', ' ', text.lower()) + ' '.join(emoticons).replace('-', ''))
        return text

    ds_data['Review'] = ds_data['Review'].apply(preprocessor)

    # Делим данные на тестовые и тренинговые. Доля тренинговых  - 20%
    X_train, X_test, y_train, y_test = train_test_split(ds_data['Review'], ds_data['Grade'], test_size=0.2,
                                                        random_state=0)

    vectors = TfidfVectorizer(strip_accents=None,
                              lowercase=False,
                              preprocessor=None,
                              token_pattern=None)
    param_grid = [{'vect__ngram_range': [(1, 1)],
                   'vect__stop_words': [stop_words, None],
                   'vect__tokenizer': [tokenizer, tokenizer_porter],
                   'clf__penalty': ['l1', 'l2'],
                   'clf__C': [1.0, 10.0, 100.0]},
                  {'vect__ngram_range': [(1, 1)],
                   'vect__stop_words': [stop_words, None],
                   'vect__tokenizer': [tokenizer, tokenizer_porter],
                   'vect__use_idf': [False],
                   'vect__norm': [None],
                   'clf__penalty': ['l1', 'l2'],
                   'clf__C': [1.0, 10.0, 100.0]}]
    model = LogisticRegression(random_state=0, solver='liblinear', max_iter=250)

    pipeline = Pipeline([('vect', vectors),
                         ('clf', model)])

    grid_search_mdl = GridSearchCV(pipeline, param_grid,
                                   scoring='accuracy',
                                   cv=5, verbose=2,
                                   n_jobs=-1)

    grid_search_mdl.fit(X_train, y_train)

    pickle.dump(grid_search_mdl, open(filename, 'wb'))

    score = grid_search_mdl.best_score_

    if score > 0.7:
        return '1'
    else:
        return '0'


if __name__ == "__main__":
    app.run(host='0.0.0.0', port=5000, debug=True)
