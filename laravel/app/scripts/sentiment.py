#!/usr/bin/env python
from senti_classifier import senti_classifier
import argparse

try:
    import json
except ImportError:
    import simplejson as json

parser = argparse.ArgumentParser(description='Figure out sentiment')
parser.add_argument('--text', action='store', default='test',
                           help='The Text to be judged')

args = parser.parse_args()
sentences = json.loads(args.text)
pos_score, neg_score = senti_classifier.polarity_scores(sentences)

result = {'positive': pos_score, 'negative': neg_score}

print json.dumps(result)