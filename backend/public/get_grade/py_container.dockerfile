FROM python:3.8
COPY . /backend/public/get_grade
WORKDIR /backend/public/get_grade

RUN pip install pandas
RUN pip install nltk
RUN pip install scikit-learn
RUN pip install Flask==2.0.3
RUN pip install requests==2.25.1
RUN pip install Werkzeug==2.2.2

ENTRYPOINT [ "python" ]
CMD [ "app.py" ]