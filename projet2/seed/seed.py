import json
import requests
import pandas as pd
import random
def delete_a(a):
    if a[:2]=="a " or a[:2]=="A ":
        a=a[2:]
    return a
        
    
def fetch_conceptnet_facts(limit=100, relations=[]):
    base_url = "http://api.conceptnet.io/c/{lang}/{concept}?limit=100"
    concepts_fr = [
        "maison", "chat", "forêt", "théâtre", "montre",
        "chaussures", "cinéma", "hôpital", "voyage", "photographie",
        "jardin", "horloge", "feu", "bibliothèque", "hôtel",
        "océan", "football", "épicerie", "café", "cirque"
    ]
    concepts_en = [
        "car", "dentiste", "banana", "music", "airplane",
        "television", "guitar", "piano", "hospital", "telephone",
        "restaurant", "basketball", "internet", "store", "pizza",
        "robot", "star", "video game", "parc", "bicycle"
    ]

    facts = []

    languages = ['fr', 'en']
    
    concepts = concepts_fr + concepts_en

    random.shuffle(concepts)


    for concept in concepts:
        if concept in concepts_fr:
            language = 'fr'
        else:
            language = 'en'

        processed_relations = set(relations)

        # Fetch ConceptNet data
        url = base_url.format(lang=language, concept=concept.replace(" ", "_"))
        response = requests.get(url)
        data = response.json()

        for edge in data['edges']:

            if 'language' not in edge['start'] or 'language' not in edge['end']:
                continue

            if edge['start']['language'] not in languages or edge['end']['language'] not in languages:
                continue

            start = edge['start']['label']
            end = edge['end']['label']
            rel = edge['rel']['label']

            if start == end or rel not in processed_relations:
                continue

            facts.append((delete_a(start), rel, delete_a(end)))
            processed_relations.remove(rel)

            if len(processed_relations) == 0:
                break

    return pd.DataFrame(facts, columns=['start', 'relation', 'end'])


relations = ['RelatedTo', 'UsedFor', 'AtLocation', 'FormOf', 'PartOf',
             'CapableOf', 'SimilarTo', 'IsA', 'MadeOf', 'HasA']

facts_df = fetch_conceptnet_facts(limit=200, relations=relations)

facts_df.to_html('facts_table.html', index=False)

with open('facts.json', 'w') as json_file:
    json.dump({'records': facts_df.to_dict(orient='records')}, json_file)

print(facts_df.shape)
